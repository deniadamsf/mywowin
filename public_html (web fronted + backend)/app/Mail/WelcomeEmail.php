<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Daftarkan dua variabel ini agar bisa dibaca oleh file tampilan (Blade)
    public $user;
    public $otp;

    // 2. Tangkap data dari controller saat email dibuat
    public function __construct($user, $otp = null)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build()
    {
        // 3. Tambahkan Subjek Email agar terlihat resmi
        return $this->subject('Selamat Datang di Wowin Food - Kode OTP Anda')
                    ->view('public.emails.welcome'); 
    }
}