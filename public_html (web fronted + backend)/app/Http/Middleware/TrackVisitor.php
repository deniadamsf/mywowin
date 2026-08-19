<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Auth;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        if ($ip === '127.0.0.1' || $ip === '::1') {
            $country = 'Localhost';
            $city = 'Local';
        } else {
            $position = Location::get($ip);
            $country = $position?->countryName ?? 'unknown';
            $city = $position?->cityName ?? 'unknown';
        }

        // Jika belum login, batasi pencatatan 1x per hari
        if (!Auth::check()) {
            $alreadyLogged = Visitor::where('ip', $ip)
                ->whereDate('visited_at', now()->toDateString())
                ->exists();

            if ($alreadyLogged) {
                return $next($request);
            }
        }

        // Catat pengunjung
        Visitor::create([
            'ip' => $ip,
            'country' => $country,
            'city' => $city,
            'user_agent' => $userAgent,
            'visited_at' => now(),
        ]);

        return $next($request);
    }
}
