<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    
    public function store(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'nullable|string|max:255',
        'email'        => 'nullable|email',
        'no_hp'        => 'nullable|numeric',
        'pesan'        => 'nullable|string|max:500',
    ]);

    Komentar::create([
        'nama_lengkap' => $request->input('nama_lengkap'),
        'email'        => $request->input('email'),
        'no_hp'        => $request->input('no_hp'),
        'pesan'        => $request->input('pesan'),
    ]);

    return redirect()->route('contacts')->with('success', 'Komentar berhasil dikirim!');
}

}

// 'nama_lengkap',
// 'email',
// 'no_hp',
// 'pesan',
