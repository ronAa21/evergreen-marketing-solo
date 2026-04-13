# Ngrok Setup for Evergreen Project

This guide will help you set up ngrok to expose your local Evergreen project to the internet.

## Prerequisites

1. **Install ngrok**: Download and install ngrok from [https://ngrok.com/download](https://ngrok.com/download)
2. **Get your authtoken**: Sign up at [https://dashboard.ngrok.com](https://dashboard.ngrok.com) and get your authtoken

## Setup Steps

### 1. Configure ngrok

1. Open `ngrok.yml` in the project root
2. Add your ngrok authtoken:
   ```yaml
   authtoken: YOUR_AUTHTOKEN_HERE
   ```
3. (Optional) If you have a custom domain, add it:
   ```yaml
   hostname: your-domain.ngrok.io
   ```

### 2. Verify XAMPP Port

By default, XAMPP Apache runs on port **80**. If your XAMPP is configured to use a different port (e.g., 8080), update the `addr` field in `ngrok.yml`:

```yaml
addr: 8080  # Change this if your XAMPP uses a different port
```

### 3. Start ngrok

**Option A: Using PowerShell (Recommended for Windows)**
```powershell
.\start-ngrok.ps1
```

**Option B: Using Batch File**
```cmd
start-ngrok.bat
```

**Option C: Manual Command**
```bash
ngrok start --config ngrok.yml evergreen
```

**Option D: Quick Start (without config file)**
```bash
ngrok http 80
```

## Accessing Your Project

Once ngrok is running, you'll see output like:

```
Forwarding  https://abc123.ngrok.io -> http://localhost:80
```

You can access your project at:
- **Main project**: `https://abc123.ngrok.io/Evergreen/`
- **Accounting & Finance**: `https://abc123.ngrok.io/Evergreen/accounting-and-finance/`
- **Bank System**: `https://abc123.ngrok.io/Evergreen/bank-system/Basic-operation/`
- **HRIS-SIA**: `https://abc123.ngrok.io/Evergreen/hris-sia/`
- **Loan Subsystem**: `https://abc123.ngrok.io/Evergreen/LoanSubsystem/`

## Ngrok Web Interface

While ngrok is running, you can access the ngrok web interface at:
- **Local**: http://localhost:4040
- This shows all requests, responses, and allows you to replay requests

## Important Notes

1. **HTTPS**: The configuration uses `bind_tls: true` which means ngrok will provide HTTPS URLs
2. **Inspection**: Request inspection is enabled by default (you can disable it in `ngrok.yml`)
3. **Free Tier**: Free ngrok accounts have limitations:
   - Random URLs that change on each restart
   - Limited connections per minute
   - Session timeout after 2 hours of inactivity
4. **Port Conflicts**: Make sure XAMPP Apache is running before starting ngrok

## Troubleshooting

### Port Already in Use
If you get a "port already in use" error:
- Check if another ngrok instance is running
- Verify XAMPP Apache is running on the correct port
- Try changing the port in `ngrok.yml`

### Connection Refused
- Ensure XAMPP Apache is running
- Verify the port number in `ngrok.yml` matches your XAMPP configuration
- Check Windows Firewall settings

### Authtoken Error
- Make sure you've added your authtoken to `ngrok.yml`
- Verify the authtoken is correct at [https://dashboard.ngrok.com](https://dashboard.ngrok.com)

## Advanced Configuration

For more advanced options, see the [ngrok configuration documentation](https://ngrok.com/docs/ngrok-agent/config).


