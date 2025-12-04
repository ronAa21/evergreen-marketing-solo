# PowerShell script to start ngrok for Evergreen project
# Make sure ngrok is installed and in your PATH

Write-Host "Starting ngrok for Evergreen project..." -ForegroundColor Green
Write-Host "Tunneling localhost:80 to the internet" -ForegroundColor Yellow
Write-Host ""

# Check if ngrok is installed
try {
    $ngrokVersion = ngrok version
    Write-Host "ngrok found: $ngrokVersion" -ForegroundColor Green
} catch {
    Write-Host "Error: ngrok is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install ngrok from https://ngrok.com/download" -ForegroundColor Yellow
    exit 1
}

# Start ngrok with the config file
ngrok start --config ngrok.yml evergreen



