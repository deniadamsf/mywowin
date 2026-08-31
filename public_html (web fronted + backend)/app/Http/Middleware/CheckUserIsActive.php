<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserIsActive
{
    public function handle($request, Closure $next)
    {
        // Jika sudah login dan user belum aktif (kompatibel dengan PHP 8.2)
        if (Auth::check() && (Auth::user()->status_aktif !== 'aktif' || Auth::user()->status_aktif === 'tidak aktif' || Auth::user()->status_aktif === 0)) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'username' => 'Akun Anda belum diaktifkan oleh admin.'
            ]);
        }

        return $next($request);
    }
}

