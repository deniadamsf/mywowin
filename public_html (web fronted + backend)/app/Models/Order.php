<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai konvensi
    protected $table = 'orders';

    // Tentukan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'user_id',
        'invoice_number',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'alamat',
        'catatan',
        'paid_amount',
        'bukti_transfer',
        'paid_at',
        'delivery_date',
        'delivery_status',
        'retur_botol',
        'retur_jerigen',
        'produk_rusak_id',
        'qty_rusak',
        'foto_kerusakan',
        'tipe_retur',
        'total_potongan_retur',
        'points_used',
        'potongan_poin',
        'shipping_courier',
        'no_resi',
        'jnt_order_id',
        'jnt_des_code',
        'total_weight_kg',
        'shipping_cost',
        'shipping_status',
        'payment_deadline',
        'rejection_reason',
    ];

    protected $casts = [
        'payment_deadline' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Otomatis membatalkan pesanan transfer pending yang telah melewati batas waktu (payment_deadline)
     * dan mengembalikan poin loyalitas pengguna jika ada yang terpotong.
     */
    public static function cancelExpiredOrders(): int
    {
        $expiredOrders = self::where('status', 'pending')
            ->where('payment_method', 'transfer')
            ->where(function ($q) {
                $q->where('payment_status', 'pending')
                  ->orWhere('payment_status', 'rejected');
            })
            ->whereNull('bukti_transfer')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            if ($order->points_used > 0 && $order->user) {
                $order->user->increment('total_points', $order->points_used);
            }

            $order->update([
                'status' => 'canceled',
                'payment_status' => 'expired',
                'shipping_status' => 'Dibatalkan Otomatis',
                'catatan' => trim(($order->catatan ? $order->catatan . "\n" : '') . '[Sistem: Pesanan dibatalkan otomatis karena melewati batas waktu pembayaran 24 jam.]'),
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Menghapus file fisik bukti transfer dan mengosongkan kolom bukti_transfer
     * untuk pesanan yang usianya sudah melewati batas bulan tertentu (default: 6 bulan).
     * Berkas fisik dihapus dari disk storage, sedangkan data transaksi & keuangan tetap utuh.
     */
    public static function cleanupOldProofs(int $months = 6): int
    {
        $cutoff = Carbon::now()->subMonths($months);
        $orders = self::whereNotNull('bukti_transfer')
            ->where('created_at', '<', $cutoff)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            if ($order->bukti_transfer) {
                $cleanPath = str_replace(['storage/', 'public/'], '', $order->bukti_transfer);

                // Hapus dari disk public jika ada
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }

                // Hapus langsung jika berada di path fisik public/storage
                $fullPath = public_path('storage/' . $cleanPath);
                if (file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }

            $order->update(['bukti_transfer' => null]);
            $count++;
        }

        return $count;
    }

    // Relasi: Order dimiliki oleh User (member)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function membership()
{
    return $this->belongsTo(Membership::class);
}

// Relasi ke Produk yang Rusak/Cacat (Penting untuk ambil harga satuan)
    public function product_rusak()
    {
        // Primary key tabel product kamu adalah id_product
        return $this->belongsTo(Product::class, 'produk_rusak_id', 'id_product');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id', 'id');
    }

    public function getIsReviewedAttribute()
    {
        return $this->reviews()->exists();
    }
}
