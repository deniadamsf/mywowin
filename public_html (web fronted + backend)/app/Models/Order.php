<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

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
