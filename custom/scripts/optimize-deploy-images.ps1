[CmdletBinding()]
param(
    [string]$TargetPath = '.deploy\uploads',

    [ValidateRange(800, 8192)]
    [int]$MaxDimension = 2560,

    [ValidateRange(60, 95)]
    [int]$JpegQuality = 82
)

$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$projectPrefix = $projectRoot.TrimEnd([System.IO.Path]::DirectorySeparatorChar) + [System.IO.Path]::DirectorySeparatorChar

if (-not [System.IO.Path]::IsPathRooted($TargetPath)) {
    $TargetPath = Join-Path $projectRoot $TargetPath
}

$targetRoot = (Resolve-Path -LiteralPath $TargetPath).Path
$targetPrefix = $targetRoot.TrimEnd([System.IO.Path]::DirectorySeparatorChar) + [System.IO.Path]::DirectorySeparatorChar

if (-not $targetRoot.StartsWith($projectPrefix, [System.StringComparison]::OrdinalIgnoreCase)) {
    throw "O diretorio de destino precisa estar dentro do projeto: $projectRoot"
}

Add-Type -AssemblyName System.Drawing

$jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() |
    Where-Object { $_.MimeType -eq 'image/jpeg' } |
    Select-Object -First 1

if ($null -eq $jpegCodec) {
    throw 'Codec JPEG nao encontrado no Windows.'
}

function Set-FisicaExifOrientation {
    param([System.Drawing.Image]$Image)

    try {
        $orientationProperty = $Image.GetPropertyItem(0x0112)
        $orientation = [System.BitConverter]::ToUInt16($orientationProperty.Value, 0)

        switch ($orientation) {
            2 { $Image.RotateFlip([System.Drawing.RotateFlipType]::RotateNoneFlipX) }
            3 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate180FlipNone) }
            4 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate180FlipX) }
            5 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate90FlipX) }
            6 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate90FlipNone) }
            7 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate270FlipX) }
            8 { $Image.RotateFlip([System.Drawing.RotateFlipType]::Rotate270FlipNone) }
        }
    } catch [System.ArgumentException] {
        # A maioria das imagens nao possui a propriedade EXIF de orientacao.
    }
}

function New-FisicaOptimizedBitmap {
    param(
        [System.Drawing.Image]$Source,
        [int]$Width,
        [int]$Height,
        [bool]$PreserveAlpha
    )

    $pixelFormat = if ($PreserveAlpha) {
        [System.Drawing.Imaging.PixelFormat]::Format32bppArgb
    } else {
        [System.Drawing.Imaging.PixelFormat]::Format24bppRgb
    }

    $bitmap = New-Object System.Drawing.Bitmap($Width, $Height, $pixelFormat)
    $bitmap.SetResolution(96, 96)
    $graphics = [System.Drawing.Graphics]::FromImage($bitmap)

    try {
        if ($PreserveAlpha) {
            $graphics.CompositingMode = [System.Drawing.Drawing2D.CompositingMode]::SourceCopy
            $graphics.Clear([System.Drawing.Color]::Transparent)
        } else {
            $graphics.Clear([System.Drawing.Color]::White)
        }

        $graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality
        $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
        $graphics.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
        $graphics.DrawImage($Source, 0, 0, $Width, $Height)
    } finally {
        $graphics.Dispose()
    }

    return $bitmap
}

$imageFiles = @(
    Get-ChildItem -LiteralPath $targetRoot -File -Recurse -Force |
        Where-Object { $_.Extension -match '^\.(jpe?g|png)$' }
)

$beforeTotal = ($imageFiles | Measure-Object Length -Sum).Sum
$results = New-Object System.Collections.Generic.List[object]
$processed = 0
$optimized = 0
$kept = 0
$failed = 0

Write-Host "Otimizando $($imageFiles.Count) imagens em $targetRoot"
Write-Host "Perfil: lado maximo de $MaxDimension px; JPEG qualidade $JpegQuality"

foreach ($file in $imageFiles) {
    $processed++
    $relativePath = $file.FullName.Substring($targetPrefix.Length)
    $temporaryPath = $file.FullName + '.fisica-optimize-' + [System.Guid]::NewGuid().ToString('N') + '.tmp'
    $source = $null
    $bitmap = $null

    try {
        $source = [System.Drawing.Image]::FromFile($file.FullName)
        Set-FisicaExifOrientation -Image $source

        $scale = [Math]::Min(1.0, $MaxDimension / [double][Math]::Max($source.Width, $source.Height))
        $width = [Math]::Max(1, [int][Math]::Round($source.Width * $scale))
        $height = [Math]::Max(1, [int][Math]::Round($source.Height * $scale))
        $isPng = $file.Extension -ieq '.png'
        $bitmap = New-FisicaOptimizedBitmap -Source $source -Width $width -Height $height -PreserveAlpha $isPng

        if ($isPng) {
            $bitmap.Save($temporaryPath, [System.Drawing.Imaging.ImageFormat]::Png)
        } else {
            $encoderParameters = New-Object System.Drawing.Imaging.EncoderParameters(1)
            $encoderParameters.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter(
                [System.Drawing.Imaging.Encoder]::Quality,
                [long]$JpegQuality
            )

            try {
                $bitmap.Save($temporaryPath, $jpegCodec, $encoderParameters)
            } finally {
                $encoderParameters.Dispose()
            }
        }

        $bitmap.Dispose()
        $bitmap = $null
        $source.Dispose()
        $source = $null

        $validationImage = [System.Drawing.Image]::FromFile($temporaryPath)
        try {
            if ($validationImage.Width -ne $width -or $validationImage.Height -ne $height) {
                throw "Dimensoes invalidas apos a otimizacao: $relativePath"
            }
        } finally {
            $validationImage.Dispose()
        }

        $optimizedFile = Get-Item -LiteralPath $temporaryPath
        $beforeBytes = $file.Length
        $afterBytes = $optimizedFile.Length

        if ($afterBytes -lt $beforeBytes) {
            [System.IO.File]::Copy($temporaryPath, $file.FullName, $true)
            Remove-Item -LiteralPath $temporaryPath -Force
            [System.IO.File]::SetLastWriteTimeUtc($file.FullName, [DateTime]::UtcNow)
            $optimized++
            $status = 'optimized'
        } else {
            Remove-Item -LiteralPath $temporaryPath -Force
            $afterBytes = $beforeBytes
            $kept++
            $status = 'kept-original'
        }

        $results.Add([pscustomobject]@{
            Path = $relativePath
            Status = $status
            BeforeBytes = $beforeBytes
            AfterBytes = $afterBytes
            ReductionPercent = [Math]::Round((1 - ($afterBytes / [double]$beforeBytes)) * 100, 2)
            Width = $width
            Height = $height
        })
    } catch {
        $failed++
        $results.Add([pscustomobject]@{
            Path = $relativePath
            Status = 'failed'
            BeforeBytes = $file.Length
            AfterBytes = $file.Length
            ReductionPercent = 0
            Width = $null
            Height = $null
            Error = $_.Exception.Message
        })

        Write-Warning "$relativePath`: $($_.Exception.Message)"
    } finally {
        if ($null -ne $bitmap) {
            $bitmap.Dispose()
        }

        if ($null -ne $source) {
            $source.Dispose()
        }

        if (Test-Path -LiteralPath $temporaryPath) {
            Remove-Item -LiteralPath $temporaryPath -Force
        }
    }

    if (0 -eq ($processed % 10) -or $processed -eq $imageFiles.Count) {
        Write-Host "Progresso: $processed/$($imageFiles.Count)"
    }
}

$currentFiles = @(
    Get-ChildItem -LiteralPath $targetRoot -File -Recurse -Force |
        Where-Object { $_.Extension -match '^\.(jpe?g|png)$' }
)
$afterTotal = ($currentFiles | Measure-Object Length -Sum).Sum
$reportPath = Join-Path (Split-Path -Parent $targetRoot) ('image-optimization-report-' + (Get-Date -Format 'yyyyMMdd-HHmmss') + '.csv')
$results | Export-Csv -LiteralPath $reportPath -NoTypeInformation -Encoding UTF8

Write-Host "Concluido."
Write-Host "Otimizadas: $optimized"
Write-Host "Originais mantidos por ja serem menores: $kept"
Write-Host "Falhas: $failed"
Write-Host "Antes: $([Math]::Round($beforeTotal / 1MB, 2)) MB"
Write-Host "Depois: $([Math]::Round($afterTotal / 1MB, 2)) MB"
Write-Host "Reducao: $([Math]::Round((1 - ($afterTotal / [double]$beforeTotal)) * 100, 2))%"
Write-Host "Relatorio: $reportPath"

if ($failed -gt 0) {
    exit 1
}
