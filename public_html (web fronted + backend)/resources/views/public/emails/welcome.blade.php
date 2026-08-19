<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Wowin Food</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f5f5f5; 
            margin: 0; 
            padding: 0; 
        }
        .email-container { 
            max-width: 600px; 
            margin: 40px auto; 
            background-color: #ffffff; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
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
        }
        .body-content { 
            padding: 30px 40px; 
            color: #444444; 
            line-height: 1.6; 
            font-size: 15px;
        }
        .greeting {
            font-size: 18px;
            color: #111111;
            margin-bottom: 20px;
        }
        .otp-container { 
            background-color: #f0f7f1; 
            border: 2px dashed #2E7D32; 
            border-radius: 8px; 
            padding: 20px; 
            text-align: center; 
            margin: 30px 0; 
        }
        .otp-title {
            font-size: 13px;
            color: #666666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .otp-code { 
            font-size: 36px; 
            font-weight: bold; 
            letter-spacing: 8px; 
            color: #1B5E20; 
            margin: 0;
        }
        .warning-text {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            font-size: 13px;
            color: #666666;
            margin-bottom: 20px;
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
            <div class="greeting">Halo, <strong>{{ $user->nama_lengkap }}</strong>!</div>
            
            <p>Terima kasih telah mendaftar sebagai Mitra Wowin Food. Untuk mengaktifkan akun aplikasi Anda, silakan masukkan kode verifikasi (OTP) 6-digit berikut:</p>

            <!-- Kotak OTP -->
            <div class="otp-container">
                <div class="otp-title">KODE VERIFIKASI ANDA</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <!-- Pesan Peringatan -->
            <div class="warning-text">
                <strong>PENTING:</strong> Jangan berikan kode ini kepada siapapun. Tim Wowin Food tidak akan pernah meminta kode sandi maupun OTP Anda dengan alasan apapun.
            </div>

            <p>Jika Anda tidak merasa mendaftar di aplikasi Wowin Food, silakan abaikan email ini.</p>
            
            <br>
            <p>Salam Hangat,<br><strong>Tim Wowin Food</strong></p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} PT. Wowin Purnomo Putera.<br>
            Trenggalek, Jawa Timur, Indonesia
        </div>
    </div>
</body>
</html>