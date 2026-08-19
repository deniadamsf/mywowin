@extends('superadmin.layouts.master') {{-- Sesuaikan dengan layout super admin Anda --}}

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-purple-800 mb-6">Upgrade Membership Manual</h1>

    <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
        <form action="{{ route('superadmin.users.upgrade.process', $user->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Member yang di-Upgrade</label>
                <input type="text" value="{{ $user->nama_lengkap }} ({{ $user->membership->nama_toko ?? '' }})" class="mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed" readonly>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Level Saat Ini</label>
                    <input type="text" value="{{ ucfirst($user->membership->level_membership) }}" class="mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed" readonly>
                </div>
                
                {{-- Input untuk Level Baru (Dropdown) --}}
                <div>
                    <label for="new_level" class="block text-sm font-medium text-purple-700">Upgrade ke Level</label>
                    <select id="new_level" name="new_level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach($availableLevels as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <label for="amount_paid" class="block text-sm font-medium text-purple-700">Jumlah Pembayaran (Rp)</label>
                <input type="number" id="amount_paid" name="amount_paid" placeholder="Contoh: 500000" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('superadmin.users.master-member') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Simpan & Upgrade
                </button>
            </div>
        </form>
    </div>
</div>
@endsection