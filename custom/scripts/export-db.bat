@echo off
REM === Configurações (pegue do wp-config.php) ===
set DB_USER=root
set DB_PASS=
set DB_NAME=fisica
set DB_HOST=localhost
set OUTPUT_DIR=custom\database_export
set FILENAME=%OUTPUT_DIR%\%DB_NAME%.sql

REM === Cria a pasta se não existir ===
if not exist %OUTPUT_DIR% mkdir %OUTPUT_DIR%

REM === Exporta o banco (usa mysqldump do XAMPP) ===
"C:\xampp\mysql\bin\mysqldump.exe" -h%DB_HOST% -u%DB_USER% %DB_NAME% > %FILENAME%

if %ERRORLEVEL% EQU 0 (
    echo ✅ Banco de dados exportado para %FILENAME%
) else (
    echo ❌ Erro ao exportar banco de dados
)

pause
