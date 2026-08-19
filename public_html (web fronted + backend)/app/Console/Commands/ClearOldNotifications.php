<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClearOldNotifications extends Command
{
    // Nama perintah yang akan dipanggil nanti
    protected $signature = 'notifications:clear-old';

    // Deskripsi perintah
    protected $description = 'Menghapus riwayat notifikasi yang berumur lebih dari 30 hari secara otomatis';

    public function handle()
    {
        // Cari tanggal persis 30 hari yang lalu dari hari ini
        $batasWaktu = Carbon::now()->subDays(30);

        // Hapus semua data di tabel yang dibuat sebelum tanggal tersebut
        $jumlahDihapus = DB::table('notifications')
                            ->where('created_at', '<', $batasWaktu)
                            ->delete();

        // Pesan sukses di terminal
        $this->info("Petugas Kebersihan sukses menghapus {$jumlahDihapus} notifikasi usang!");
    }
}