[CmdletBinding()]
param(
    [string]$OutputPath,
    [string]$DatabaseOutputPath,
    [string]$TargetUrl = 'https://fisica.uerj.br',
    [switch]$SkipDatabase
)

$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$deployDirectory = Join-Path $projectRoot '.deploy'
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'

if ([string]::IsNullOrWhiteSpace($OutputPath)) {
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

$databaseOutputFullPath = $null

if (-not $SkipDatabase) {
    if ([string]::IsNullOrWhiteSpace($DatabaseOutputPath)) {
        $archiveBaseName = [System.IO.Path]::GetFileNameWithoutExtension($outputFullPath)
        $DatabaseOutputPath = Join-Path $outputDirectory "$archiveBaseName-database.sql"
    } elseif (-not [System.IO.Path]::IsPathRooted($DatabaseOutputPath)) {
        $DatabaseOutputPath = Join-Path $projectRoot $DatabaseOutputPath
    }

    $databaseOutputFullPath = [System.IO.Path]::GetFullPath($DatabaseOutputPath)
    $databaseOutputDirectory = Split-Path -Parent $databaseOutputFullPath

    if (-not (Test-Path -LiteralPath $databaseOutputDirectory)) {
        New-Item -ItemType Directory -Path $databaseOutputDirectory -Force | Out-Null
    }

    if (Test-Path -LiteralPath $databaseOutputFullPath) {
        throw "O SQL de destino ja existe: $databaseOutputFullPath"
    }

    $phpExecutable = 'C:\xampp\php\php.exe'
    $databaseGenerator = Join-Path $PSScriptRoot 'build-idempotent-db.php'

    if (-not (Test-Path -LiteralPath $phpExecutable)) {
        throw "PHP do XAMPP nao encontrado: $phpExecutable"
    }

    if (-not (Test-Path -LiteralPath $databaseGenerator)) {
        throw "Gerador de banco nao encontrado: $databaseGenerator"
    }

    & $phpExecutable $databaseGenerator "--output=$databaseOutputFullPath" "--target-url=$TargetUrl"

    if ($LASTEXITCODE -ne 0) {
        if (Test-Path -LiteralPath $databaseOutputFullPath) {
            Remove-Item -LiteralPath $databaseOutputFullPath -Force
        }
        throw "Falha ao gerar o SQL idempotente."
    }
}

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$excludedRootDirectories = @('.git', '.agents', '.codex', '.deploy')
$excludedRootFiles = @('wp-config.php')
$excludedRelativeDirectories = @('custom\database_export')
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

    $normalizedRelativePath = $relativePath.Replace('/', '\')
    foreach ($excludedRelativeDirectory in $excludedRelativeDirectories) {
        if (
            $normalizedRelativePath -eq $excludedRelativeDirectory -or
            $normalizedRelativePath.StartsWith($excludedRelativeDirectory + '\', [StringComparison]::OrdinalIgnoreCase)
        ) {
            return $false
        }
    }

    # Exclui somente itens temporarios que partem da raiz do projeto.
    foreach ($pattern in $temporaryRootPatterns) {
        if ($segments[0] -like $pattern) {
            return $false
        }
    }

    if ($_.FullName -eq $outputFullPath) {
        return $false
    }

    if ($null -ne $databaseOutputFullPath -and $_.FullName -eq $databaseOutputFullPath) {
        return $false
    }

    return $true
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

if ($null -ne $databaseOutputFullPath) {
    $databaseInfo = Get-Item -LiteralPath $databaseOutputFullPath
    $databaseSizeMb = [Math]::Round($databaseInfo.Length / 1MB, 2)
    Write-Host "SQL idempotente: $databaseOutputFullPath"
    Write-Host "Tamanho do SQL: $databaseSizeMb MB"
}
