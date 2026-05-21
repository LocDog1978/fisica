$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression.FileSystem

$path = 'C:\xampp\htdocs\fisica\wp-content\uploads\2026\05\Projetos_EXT_FIS_Site_do_IF.xlsx'
$zip = [System.IO.Compression.ZipFile]::OpenRead($path)

try {
    $sharedStrings = @()
    $sharedEntry = $zip.GetEntry('xl/sharedStrings.xml')

    if ($sharedEntry) {
        $sharedReader = New-Object System.IO.StreamReader($sharedEntry.Open())
        try {
            [xml]$sharedXml = $sharedReader.ReadToEnd()
        }
        finally {
            $sharedReader.Dispose()
        }

        foreach ($si in $sharedXml.sst.si) {
            if ($si.t) {
                $sharedStrings += [string]$si.t
            }
            elseif ($si.r) {
                $text = ''
                foreach ($run in $si.r) {
                    $text += [string]$run.t
                }
                $sharedStrings += $text
            }
            else {
                $sharedStrings += ''
            }
        }
    }

    $sheetEntry = $zip.GetEntry('xl/worksheets/sheet1.xml')
    $sheetReader = New-Object System.IO.StreamReader($sheetEntry.Open())
    try {
        [xml]$sheetXml = $sheetReader.ReadToEnd()
    }
    finally {
        $sheetReader.Dispose()
    }

    foreach ($row in $sheetXml.worksheet.sheetData.row) {
        $cells = [ordered]@{}

        foreach ($cell in $row.c) {
            $ref = [string]$cell.r
            $col = ($ref -replace '\d', '')
            $type = [string]$cell.t
            $value = ''

            if ($type -eq 's') {
                $index = [int]$cell.v
                if ($index -lt $sharedStrings.Count) {
                    $value = $sharedStrings[$index]
                }
            }
            elseif ($cell.is -and $cell.is.t) {
                $value = [string]$cell.is.t
            }
            elseif ($cell.v) {
                $value = [string]$cell.v
            }

            $cells[$col] = $value
        }

        $json = $cells | ConvertTo-Json -Compress
        Write-Output ("ROW {0}: {1}" -f [string]$row.r, $json)
    }
}
finally {
    $zip.Dispose()
}
