<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingVoucher extends Model
{
    use HasFactory;

    protected $table = 'shipping_vouchers';

    protected $fillable = [
        'code',
        'name',
        'description',
        'min_purchase',
        'discount_amount',
        'base_rate_per_kg',
        'rate_jawa_non_jatim',
        'is_active',
    ];

    protected $casts = [
        'min_purchase' => 'float',
        'discount_amount' => 'float',
        'base_rate_per_kg' => 'float',
        'rate_jawa_non_jatim' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Dapatkan voucher pengiriman aktif pertama (default)
     */
    public static function getActiveVoucher(): ?self
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Hitung diskon ongkir berdasarkan subtotal belanja dan total ongkir
     */
    public static function calculateDiscount(float $subtotal, float $shippingCost): array
    {
        $voucher = self::getActiveVoucher();

        if (!$voucher || $subtotal < $voucher->min_purchase) {
            return [
                'applied' => false,
                'discount' => 0.0,
                'net_shipping' => $shippingCost,
                'voucher' => $voucher,
                'shortfall' => $voucher ? max(0.0, $voucher->min_purchase - $subtotal) : 0.0,
            ];
        }

        $discount = min($shippingCost, $voucher->discount_amount);
        $netShipping = max(0.0, $shippingCost - $discount);

        return [
            'applied' => true,
            'discount' => $discount,
            'net_shipping' => $netShipping,
            'voucher' => $voucher,
            'shortfall' => 0.0,
        ];
    }
}
