<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSetting extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'branch_settings';

    // Kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'user_id',
        'enum_value',
        'nama_pt',
        'logo',
        'alamat',
        'no_telp',
        'google_maps_review_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}