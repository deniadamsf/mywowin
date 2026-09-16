<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'config',
        'sort_order',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Ambil seluruh kode metode pembayaran yang sedang aktif
     */
    public static function getActiveCodes(): array
    {
        return self::where('is_active', true)
            ->pluck('code')
            ->toArray();
    }

    /**
     * Cek apakah sebuah metode pembayaran aktif
     */
    public static function isMethodActive(string $code): bool
    {
        return self::where('code', $code)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Ambil data rekening bank dari config (hanya yang aktif atau semua)
     */
    public function getBankAccounts(bool $onlyActive = true): array
    {
        $accounts = $this->config['bank_accounts'] ?? [];
        if (!is_array($accounts)) {
            return [];
        }

        if ($onlyActive) {
            return array_values(array_filter($accounts, fn($item) => ($item['is_active'] ?? true) === true));
        }

        return $accounts;
    }

    /**
     * Ambil nomor WhatsApp admin resmi (berakhiran 6600)
     */
    public function getWaNumber(): string
    {
        $num = $this->config['phone_number'] ?? '62812106600';
        if ($num === '6281216301220' || empty($num)) {
            return '62812106600';
        }
        return $num;
    }
}
