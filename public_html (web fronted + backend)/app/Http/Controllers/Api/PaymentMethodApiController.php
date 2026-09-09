<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodApiController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $formatted = $methods->map(function ($m) {
            $data = [
                'code' => $m->code,
                'name' => $m->name,
                'description' => $m->description,
                'is_active' => (bool)$m->is_active,
            ];

            if ($m->code === 'transfer') {
                $data['bank_accounts'] = $m->getBankAccounts(true);
            } elseif ($m->code === 'wa') {
                $data['phone_number'] = $m->getWaNumber();
            }

            return $data;
        });

        $voucher = class_exists(\App\Models\ShippingVoucher::class) ? \App\Models\ShippingVoucher::getActiveVoucher() : null;
        $voucherData = $voucher ? [
            'code' => $voucher->code,
            'name' => $voucher->name,
            'description' => $voucher->description,
            'min_purchase' => (float)$voucher->min_purchase,
            'discount_amount' => (float)$voucher->discount_amount,
            'base_rate_per_kg' => (float)$voucher->base_rate_per_kg,
            'rate_jawa_non_jatim' => (float)($voucher->rate_jawa_non_jatim ?? 9500),
            'is_active' => (bool)$voucher->is_active,
        ] : null;

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
            'shipping_voucher' => $voucherData,
        ], 200);
    }

    public function getShippingVoucher()
    {
        $voucher = class_exists(\App\Models\ShippingVoucher::class) ? \App\Models\ShippingVoucher::getActiveVoucher() : null;
        return response()->json([
            'status' => 'success',
            'data' => $voucher,
        ], 200);
    }
}
