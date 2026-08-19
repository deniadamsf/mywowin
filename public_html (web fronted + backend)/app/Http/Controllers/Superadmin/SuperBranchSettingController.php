<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchSetting;
use App\Models\User; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class SuperBranchSettingController extends Controller
{
public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user && $user->role == 'admin') {
        $setting = BranchSetting::where('user_id', $user->id)->first();
        return view('admin.branch_settings.index', compact('setting'));
    } 

    // Untuk Superadmin, kita ambil semua setting cabang dan sertakan data usernya (Adminnya)
    $settings = BranchSetting::with('user')->get(); 
    
    // Kita kirimkan $settings, bukan $users
    return view('superadmin.branch_settings.index', compact('settings'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_pt' => 'required',
            'alamat'  => 'required',
            'no_telp' => 'required',
            'logo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /** @var \App\Models\User $user */ // <-- Tambahkan ini juga di sini
        $user = Auth::user(); 

        if (!$user) {
            return back()->with('error', 'Sesi login berakhir.');
        }

        $data = [
            'user_id'    => $user->id,
            'enum_value' => $user->kantor_cabang,
            'nama_pt'    => $request->nama_pt,
            'alamat'     => $request->alamat,
            'no_telp'    => $request->no_telp,
        ];

        if ($request->hasFile('logo')) {
            $existingSetting = BranchSetting::where('user_id', $user->id)->first();
            
            if ($existingSetting && $existingSetting->logo) {
                Storage::disk('public')->delete($existingSetting->logo);
            }
            
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        BranchSetting::updateOrCreate(
            ['user_id' => $user->id], 
            $data
        );

        return back()->with('success', 'Identitas PT untuk Cabang ' . $user->kantor_cabang . ' Berhasil Disimpan!');
    }
}