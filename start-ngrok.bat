@echo off
REM Batch script to start ngrok for Evergreen project
REM Make sure ngrok is installed and in your PATH

echo Starting ngrok for Evergreen project...
echo Tunneling localhost:80 to the internet
echo.

REM Check if ngrok is installed
where ngrok >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo Error: ngrok is not installed or not in PATH
    echo Please install ngrok from https://ngrok.com/download
    pause
    exit /b 1
)

REM Start ngrok with the config file
ngrok start --config ngrok.yml evergreen

pause



