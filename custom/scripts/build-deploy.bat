@echo off
setlocal
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0build-deploy.ps1" %*
exit /b %ERRORLEVEL%
