@extends('superadmin.layouts.master')
@section('title', 'Daftar Pesan Masuk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Live Chat Member</h2>
        <p class="text-sm text-gray-500">Daftar member yang mengirimkan pesan ke sistem.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($users as $user)
        <a href="{{ route('superadmin.chats.show', $user->id) }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all">
            <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden border border-purple-200 shrink-0">
                @if($user->foto_profile)
                    <img src="{{ asset('storage/' . $user->foto_profile) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-purple-700 font-bold text-lg">{{ substr($user->nama_lengkap, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold text-gray-800 truncate">{{ $user->nama_lengkap }}</h3>
                <p class="text-xs text-gray-500 truncate">{{ $user->membership->nama_toko ?? 'Member Wowin' }}</p>
                <div class="mt-2 text-xs font-semibold text-purple-600 bg-purple-50 inline-block px-2 py-1 rounded">Buka Obrolan &rarr;</div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-10 text-center text-gray-500">
            Belum ada pesan masuk dari member.
        </div>
        @endforelse
    </div>
</div>
@endsection