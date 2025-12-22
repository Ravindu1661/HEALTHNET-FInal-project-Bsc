<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .otp-box {
            background: #f7fafc;
            border: 2px dashed #2563eb;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .footer {
            background: #f7fafc;
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔒 HEALTHNET Password Reset</h1>
        </div>
        <div class="content">
            <h2>Password Reset Request</h2>
            <p>Your OTP for resetting your password:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <p style="color: #718096; margin: 10px 0 0 0;">This OTP is valid for 10 minutes</p>
            </div>

            <p style="color: #4a5568;">
                If you did not make this request, please ignore this email.
            </p>
        </div>
        <div class="footer">
            <p>© 2025 HEALTHNET. All rights reserved.</p>
            <p>Do not share this OTP with anyone.</p>
        </div>
    </div>
</body>
</html>
