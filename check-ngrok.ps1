# Check ngrok status and get public URL
Start-Sleep -Seconds 2

try {
    $response = Invoke-RestMethod -Uri 'http://localhost:4040/api/tunnels' -ErrorAction Stop
    $tunnel = $response.tunnels | Where-Object { $_.proto -eq 'https' } | Select-Object -First 1
    
    if ($tunnel) {
        Write-Host "`n========================================" -ForegroundColor Green
        Write-Host "   NGROK IS ONLINE!" -ForegroundColor Green
        Write-Host "========================================`n" -ForegroundColor Green
        
        Write-Host "Public URL:" -ForegroundColor Yellow
        Write-Host "  $($tunnel.public_url)" -ForegroundColor Cyan
        Write-Host ""
        Write-Host "Local URL:" -ForegroundColor Yellow
        Write-Host "  $($tunnel.config.addr)" -ForegroundColor Cyan
        Write-Host ""
        Write-Host "Access your Evergreen project at:" -ForegroundColor Green
        Write-Host "  $($tunnel.public_url)/Evergreen/" -ForegroundColor Cyan
        Write-Host ""
        Write-Host "Ngrok Web Interface:" -ForegroundColor Yellow
        Write-Host "  http://localhost:4040" -ForegroundColor Cyan
        Write-Host ""
    } else {
        Write-Host "Ngrok is running but no HTTPS tunnel found" -ForegroundColor Yellow
    }
} catch {
    Write-Host "`nNgrok may still be starting or there was an error." -ForegroundColor Yellow
    Write-Host "Check http://localhost:4040 for the ngrok web interface`n" -ForegroundColor Yellow
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
}



