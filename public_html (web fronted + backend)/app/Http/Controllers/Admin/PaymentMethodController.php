<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();
        $shippingVoucher = class_exists(\App\Models\ShippingVoucher::class) ? \App\Models\ShippingVoucher::firstOrCreate(
            ['code' => 'ONGKIR4500'],
            [
                'name' => 'Voucher Diskon Ongkir Rp 4.500',
                'description' => 'Minimal belanja Rp 10.000 (Gratis Ongkir s/d 1 Kg)',
                'min_purchase' => 10000.00,
                'discount_amount' => 4500.00,
                'base_rate_per_kg' => 4500.00,
                'is_active' => true,
            ]
        ) : null;
        return view('admin.payment_methods.index', compact('paymentMethods', 'shippingVoucher'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:payment_methods,code',
        ]);

        $method = PaymentMethod::where('code', $request->code)->firstOrFail();
        $method->is_active = !$method->is_active;
        $method->save();

        $statusText = $method->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $method->is_active,
                'message' => "Metode pembayaran {$method->name} berhasil {$statusText}!",
            ]);
        }

        return redirect()->route($this->getRedirectRoute())->with('success', "Metode pembayaran {$method->name} berhasil {$statusText}!");
    }

    public function updateWa(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:25',
        ]);

        $method = PaymentMethod::where('code', 'wa')->firstOrFail();
        $config = $method->config ?? [];

        // Bersihkan format nomor WA
        $rawPhone = preg_replace('/[^0-9]/', '', $request->phone_number);
        if (str_starts_with($rawPhone, '0')) {
            $rawPhone = '62' . substr($rawPhone, 1);
        }

        $config['phone_number'] = $rawPhone;
        $method->config = $config;
        $method->save();

        return redirect()->route($this->getRedirectRoute())->with('success', 'Nomor WhatsApp resmi berhasil diperbarui ke +' . $rawPhone);
    }

    public function addBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ]);

        $method = PaymentMethod::where('code', 'transfer')->firstOrFail();
        $config = $method->config ?? [];
        $accounts = $config['bank_accounts'] ?? [];

        $newAccount = [
            'id' => 'bank_' . uniqid(),
            'bank_name' => trim($request->bank_name),
            'account_number' => trim($request->account_number),
            'account_holder' => trim($request->account_holder),
            'is_active' => true,
        ];

        $accounts[] = $newAccount;
        $config['bank_accounts'] = $accounts;
        $method->config = $config;
        $method->save();

        return redirect()->route($this->getRedirectRoute())->with('success', "Rekening {$request->bank_name} berhasil ditambahkan!");
    }

    public function toggleBankAccount(Request $request, $id)
    {
        $method = PaymentMethod::where('code', 'transfer')->firstOrFail();
        $config = $method->config ?? [];
        $accounts = $config['bank_accounts'] ?? [];

        $found = false;
        foreach ($accounts as &$account) {
            if (($account['id'] ?? '') === $id) {
                $account['is_active'] = !($account['is_active'] ?? true);
                $found = true;
                break;
            }
        }

        if ($found) {
            $config['bank_accounts'] = $accounts;
            $method->config = $config;
            $method->save();
            return redirect()->route($this->getRedirectRoute())->with('success', 'Status rekening bank berhasil diubah!');
        }

        return redirect()->route($this->getRedirectRoute())->with('error', 'Rekening bank tidak ditemukan.');
    }

    public function deleteBankAccount(Request $request, $id)
    {
        $method = PaymentMethod::where('code', 'transfer')->firstOrFail();
        $config = $method->config ?? [];
        $accounts = $config['bank_accounts'] ?? [];

        $accounts = array_values(array_filter($accounts, fn($item) => ($item['id'] ?? '') !== $id));

        $config['bank_accounts'] = $accounts;
        $method->config = $config;
        $method->save();

        return redirect()->route($this->getRedirectRoute())->with('success', 'Rekening bank berhasil dihapus!');
    }

    public function updateShippingVoucher(Request $request)
    {
        $request->validate([
            'min_purchase' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'base_rate_per_kg' => 'required|numeric|min:0',
            'rate_jawa_non_jatim' => 'required|numeric|min:0',
        ]);

        $voucher = \App\Models\ShippingVoucher::firstOrCreate(
            ['code' => 'ONGKIR4500'],
            [
                'name' => 'Voucher Diskon Ongkir Rp 4.500',
                'description' => 'Minimal belanja Rp 10.000 (Gratis Ongkir s/d 1 Kg)',
                'is_active' => true,
            ]
        );

        $voucher->update([
            'min_purchase' => $request->min_purchase,
            'discount_amount' => $request->discount_amount,
            'base_rate_per_kg' => $request->base_rate_per_kg,
            'rate_jawa_non_jatim' => $request->rate_jawa_non_jatim,
            'description' => "Minimal belanja Rp " . number_format($request->min_purchase, 0, ',', '.') . " (Diskon ongkir Rp " . number_format($request->discount_amount, 0, ',', '.') . ")",
        ]);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Pengaturan tarif ongkir dan voucher berhasil diperbarui!');
    }

    public function toggleShippingVoucher(Request $request)
    {
        $voucher = \App\Models\ShippingVoucher::firstOrCreate(
            ['code' => 'ONGKIR4500'],
            [
                'name' => 'Voucher Diskon Ongkir Rp 4.500',
                'min_purchase' => 10000.00,
                'discount_amount' => 4500.00,
                'base_rate_per_kg' => 4500.00,
                'is_active' => true,
            ]
        );

        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $voucher->is_active,
                'message' => "Voucher diskon ongkir berhasil {$statusText}!",
            ]);
        }

        return redirect()->route($this->getRedirectRoute())->with('success', "Voucher diskon ongkir berhasil {$statusText}!");
    }

    private function getRedirectRoute(): string
    {
        $isSuperAdmin = request()->is('superadmin*') || (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'superadmin']));
        return $isSuperAdmin ? 'superadmin.payment_methods.index' : 'admin.payment_methods.index';
    }
}
