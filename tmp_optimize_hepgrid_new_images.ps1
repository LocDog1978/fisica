$ErrorActionPreference = 'Stop'

Add-Type -AssemblyName System.Drawing

$baseDir = 'C:\xampp\htdocs\fisica\wp-content\uploads\2026\06'
$files = @(
  'GEO_5476-2.jpg',
  'GEO_6491-2.jpg'
)

$maxWidth = 2400
$quality = 84L
$backupDir = Join-Path $baseDir ('backup-hepgrid-' + (Get-Date -Format 'yyyyMMdd-HHmmss'))
New-Item -ItemType Directory -Path $backupDir | Out-Null

$jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() |
  Where-Object { $_.MimeType -eq 'image/jpeg' }

foreach ($file in $files) {
  $path = Join-Path $baseDir $file
  $backupPath = Join-Path $backupDir $file
  Copy-Item -LiteralPath $path -Destination $backupPath

  $tempPath = "$path.optimized.jpg"
  $image = [System.Drawing.Image]::FromFile($path)
  try {
    $targetWidth = [Math]::Min($image.Width, $maxWidth)
    $targetHeight = [int][Math]::Round($image.Height * ($targetWidth / [double]$image.Width))

    $bitmap = New-Object System.Drawing.Bitmap($targetWidth, $targetHeight)
    try {
      $bitmap.SetResolution($image.HorizontalResolution, $image.VerticalResolution)
      $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
      try {
        $graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality
        $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
        $graphics.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
        $graphics.DrawImage($image, 0, 0, $targetWidth, $targetHeight)
      }
      finally {
        $graphics.Dispose()
      }

      $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
      $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, $quality)
      $bitmap.Save($tempPath, $jpegCodec, $encoderParams)
    }
    finally {
      $bitmap.Dispose()
    }
  }
  finally {
    $image.Dispose()
  }

  Remove-Item -LiteralPath $path -Force
  Move-Item -LiteralPath $tempPath -Destination $path
  Write-Output $file
}

Write-Output ("BACKUP_DIR={0}" -f $backupDir)
