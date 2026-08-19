@extends('superadmin.layouts.master')
@section('title', 'Ruang Obrolan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[calc(100vh-8rem)]">
    <!-- Header Chat -->
    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50 rounded-t-xl">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.chats.index') }}" class="p-2 hover:bg-gray-200 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-sm font-bold text-gray-800">{{ $user->nama_lengkap }}</h2>
                <p class="text-xs text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Area Chat (Bisa di-scroll) -->
    <div class="flex-1 p-6 overflow-y-auto bg-gray-50" id="chatArea">
        @forelse($chats as $chat)
            @if($chat->sender_role == 'admin')
                <!-- Balasan Admin (Kanan) -->
                <div class="flex justify-end mb-4 group">
                    <div class="flex items-center gap-2">
                        <!-- Tombol Hapus (Hanya muncul saat di-hover) -->
                        <form action="{{ route('superadmin.chats.destroy', $chat->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Hapus pesan ini secara permanen?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1 text-red-400 hover:text-red-600 rounded-full hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </form>

                        <div class="bg-purple-600 text-white px-4 py-2 rounded-2xl rounded-tr-sm shadow-sm max-w-lg">
                            <p class="text-sm">{{ $chat->message }}</p>
                            <span class="text-[10px] text-purple-200 mt-1 block text-right">{{ $chat->created_at->format('d M H:i') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Pesan User (Kiri) -->
                <div class="flex justify-start mb-4 group">
                    <div class="flex items-center gap-2">
                        <div class="bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-tl-sm shadow-sm max-w-lg">
                            <p class="text-sm">{{ $chat->message }}</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">{{ $chat->created_at->format('d M H:i') }}</span>
                        </div>

                        <!-- Tombol Hapus Pesan User (Oleh Admin) -->
                        <form action="{{ route('superadmin.chats.destroy', $chat->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Hapus pesan member ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1 text-red-400 hover:text-red-600 rounded-full hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @empty
            <div class="flex h-full items-center justify-center text-gray-400 text-sm">
                Belum ada percakapan dengan member ini.
            </div>
        @endforelse
    </div>

    <!-- Kolom Ketik Pesan -->
    <div class="p-4 bg-white border-t border-gray-100 rounded-b-xl">
        <form action="{{ route('superadmin.chats.reply', $user->id) }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="message" required autocomplete="off" placeholder="Ketik balasan untuk {{ $user->nama_lengkap }}..." class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-4 py-2.5 text-sm focus:outline-none focus:border-purple-400 focus:ring-1 focus:ring-purple-400">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white rounded-full p-3 transition-colors shadow-sm flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 transform -rotate-45 ml-1 mb-1">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    // Auto-scroll ke pesan paling bawah saat halaman dimuat
    const chatArea = document.getElementById('chatArea');
    chatArea.scrollTop = chatArea.scrollHeight;
</script>
@endsection