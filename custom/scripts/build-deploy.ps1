[CmdletBinding()]
param(
    [string]$OutputPath
)

$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$deployDirectory = Join-Path $projectRoot '.deploy'

if ([string]::IsNullOrWhiteSpace($OutputPath)) {
    $timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
    $OutputPath = Join-Path $deployDirectory "fisica-deploy-$timestamp.zip"
} elseif (-not [System.IO.Path]::IsPathRooted($OutputPath)) {
    $OutputPath = Join-Path $projectRoot $OutputPath
}

$outputFullPath = [System.IO.Path]::GetFullPath($OutputPath)
$outputDirectory = Split-Path -Parent $outputFullPath

if (-not (Test-Path -LiteralPath $outputDirectory)) {
    New-Item -ItemType Directory -Path $outputDirectory -Force | Out-Null
}

if (Test-Path -LiteralPath $outputFullPath) {
    throw "O arquivo de destino ja existe: $outputFullPath"
}

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$excludedRootDirectories = @('.git', '.agents', '.codex', '.deploy')
$excludedRootFiles = @('wp-config.php')
$temporaryRootPatterns = @('tmp_*', 'tmp-*', '.tmp*', 'temp_*', 'temp-*')
$rootPrefix = $projectRoot.TrimEnd('\') + '\'
$files = Get-ChildItem -LiteralPath $projectRoot -File -Recurse -Force | Where-Object {
    $relativePath = $_.FullName.Substring($rootPrefix.Length)
    $segments = $relativePath -split '[\\/]'

    if ($segments[0] -in $excludedRootDirectories) {
        return $false
    }

    if ($segments.Count -eq 1 -and $segments[0] -in $excludedRootFiles) {
        return $false
    }

    # Exclui somente itens temporarios que partem da raiz do projeto.
    foreach ($pattern in $temporaryRootPatterns) {
        if ($segments[0] -like $pattern) {
            return $false
        }
    }

    return $_.FullName -ne $outputFullPath
}

$excludedTemporaryItems = Get-ChildItem -LiteralPath $projectRoot -Force | Where-Object {
    foreach ($pattern in $temporaryRootPatterns) {
        if ($_.Name -like $pattern) {
            return $true
        }
    }
    return $false
}

$archive = [System.IO.Compression.ZipFile]::Open(
    $outputFullPath,
    [System.IO.Compression.ZipArchiveMode]::Create
)

try {
    foreach ($file in $files) {
        $relativePath = $file.FullName.Substring($rootPrefix.Length).Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $archive,
            $file.FullName,
            $relativePath,
            [System.IO.Compression.CompressionLevel]::Optimal
        ) | Out-Null
    }
} catch {
    $archive.Dispose()
    if (Test-Path -LiteralPath $outputFullPath) {
        Remove-Item -LiteralPath $outputFullPath -Force
    }
    throw
} finally {
    if ($null -ne $archive) {
        $archive.Dispose()
    }
}

$archiveInfo = Get-Item -LiteralPath $outputFullPath
$sizeMb = [Math]::Round($archiveInfo.Length / 1MB, 2)

Write-Host "Pacote criado com sucesso: $outputFullPath"
Write-Host "Arquivos incluidos: $($files.Count)"
Write-Host "Itens temporarios da raiz ignorados: $($excludedTemporaryItems.Count)"
Write-Host "Tamanho: $sizeMb MB"
