<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password Wowin Food</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f5f5f5; 
            margin: 0; 
            padding: 0; 
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .email-container { 
            max-width: 600px; 
            margin: 30px auto; 
            background-color: #ffffff; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: 1px solid #e0e0e0;
        }
        .header { 
            background-color: #1B5E20; /* Hijau Wowin */
            padding: 25px; 
            text-align: center; 
        }
        .header h1 { 
            color: #ffffff; 
            margin: 0; 
            font-size: 24px; 
            letter-spacing: 1.5px;
            font-weight: 700;
        }
        .body-content { 
            padding: 30px 40px; 
            color: #333333; 
            line-height: 1.6; 
            font-size: 15px;
        }
        .greeting {
            font-size: 18px;
            color: #111111;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .otp-container { 
            background-color: #f0f7f1; 
            border: 2px dashed #2E7D32; 
            border-radius: 8px; 
            padding: 20px; 
            text-align: center; 
            margin: 25px 0; 
        }
        .otp-title {
            font-size: 13px;
            color: #555555;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .otp-code { 
            font-size: 36px; 
            font-weight: bold; 
            letter-spacing: 8px; 
            color: #1B5E20; 
            margin: 0;
        }
        .otp-expiry {
            font-size: 12px;
            color: #777777;
            margin-top: 8px;
        }
        .warning-text {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            font-size: 13px;
            color: #666666;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }
        .footer { 
            background-color: #fafafa; 
            padding: 20px; 
            text-align: center; 
            font-size: 12px; 
            color: #888888; 
            border-top: 1px solid #eeeeee; 
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>WOWIN FOOD</h1>
        </div>
        
        <!-- Isi Email -->
        <div class="body-content">
            <div class="greeting">Halo, {{ $user->nama_lengkap ?? $user->username }}!</div>
            
            <p>Kami menerima permintaan untuk mengatur ulang kata sandi (reset password) akun Anda di aplikasi <strong>Wowin Food</strong>. Gunakan kode verifikasi (OTP) 6-digit di bawah ini:</p>

            <!-- Kotak OTP -->
            <div class="otp-container">
                <div class="otp-title">KODE OTP RESET PASSWORD</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">Kode ini berlaku selama 15 menit.</div>
            </div>

            <!-- Pesan Peringatan Keamanan -->
            <div class="warning-text">
                <strong>PENTING:</strong> Jangan berikan kode OTP ini kepada siapapun termasuk pihak yang mengatasnamakan Wowin Food.
            </div>

            <p>Jika Anda tidak merasa meminta reset password ini, abaikan email ini. Kata sandi akun Anda tidak akan berubah tanpa memasukkan kode ini.</p>
            
            <br>
            <p>Salam Hormat,<br><strong>Tim Wowin Food</strong></p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} PT. Wowin Purnomo Putera.<br>
            Trenggalek, Jawa Timur, Indonesia
        </div>
    </div>
</body>
</html>
