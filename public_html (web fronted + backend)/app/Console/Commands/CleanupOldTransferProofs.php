<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CleanupOldTransferProofs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cleanup-old-proofs {--months=6 : Usia bukti transfer dalam satuan bulan (default: 6)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus berkas fisik bukti transfer yang berumur lebih dari 6 bulan secara otomatis untuk menghemat kapasitas storage server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $months = (int) $this->option('months');
        if ($months <= 0) {
            $months = 6;
        }

        $this->info("Memulai pembersihan bukti transfer pesanan yang berumur lebih dari {$months} bulan...");

        $deletedCount = Order::cleanupOldProofs($months);

        $this->info("Sukses! {$deletedCount} berkas bukti transfer lama berhasil dibersihkan dari server.");

        return Command::SUCCESS;
    }
}
