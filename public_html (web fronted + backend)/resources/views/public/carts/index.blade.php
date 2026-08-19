@extends('public.layouts.app')

@section('title', 'WOWINFood - Keranjang Belanja')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="flex items-center space-x-2 bg-white px-3 sm:px-5 py-3 rounded-lg shadow-sm w-full max-w-7xl border-l-4 border-[#16782d]">
        <a href="/" class="text-gray-600 hover:text-[#16782d] font-medium flex items-center text-sm sm:text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="hidden sm:inline">Beranda</span>
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[#16782d] font-semibold flex items-center text-sm sm:text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Keranjang
        </span>
    </div>
</div>

<div class="container mx-auto px-4 py-6 font-['Poppins']">
<div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mx-auto border border-gray-100">

@if($carts->isEmpty())
    <div class="py-16 text-center">
        <div class="text-gray-300 text-6xl mb-4 animate__animated animate__fadeIn">
            <i class="bi bi-cart-x"></i>
        </div>
        <h3 class="text-xl font-medium text-gray-700 mb-2">Keranjang belanja Anda kosong</h3>
        <p class="text-gray-500 mb-6">Silakan tambahkan produk ke keranjang untuk melanjutkan belanja</p>
        <a href="{{ route('products') }}" class="inline-flex items-center bg-[#16782d] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c20] transition-colors">
            <i class="bi bi-arrow-left mr-2"></i> Lanjutkan Belanja
        </a>
    </div>
@else
@php $subtotal = 0; @endphp

<!-- Table Desktop -->
<div class="w-full max-w-full overflow-x-auto mt-6 rounded-xl border border-gray-100 shadow-sm hidden md:block">
<table class="min-w-[600px] w-full table-auto text-sm text-gray-700">
    <thead>
        <tr class="bg-[#16782d] text-white text-left text-sm">
            <th class="px-5 py-4 font-semibold rounded-tl-xl">Produk</th>
            <th class="px-5 py-4 font-semibold">Harga</th>
            <th class="px-5 py-4 font-semibold">Jumlah</th>
            <th class="px-5 py-4 font-semibold">Total</th>
            <th class="px-5 py-4 font-semibold rounded-tr-xl">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
    @foreach($carts as $cart)
       @php 
            $isBundling = $cart->bundling !== null;

            // --- LOGIKA HARGA BARU ---
            if ($isBundling) {
                $price = $cart->bundling->price;
                $itemName = $cart->bundling->nama_bundling;
            } else {
                // Ambil harga & nama dari data yang tersimpan di keranjang
                $price = $cart->price; // <-- Mengambil harga dari cart->price
                $itemName = $cart->product->nama_produk;
            }
            // --- AKHIR LOGIKA HARGA BARU ---

            $total = $price * $cart->quantity;
            $subtotal += $total;
        @endphp
        <tr class="hover:bg-gray-50 transition-all">
            <td class="p-5">
                <div class="flex items-center gap-4">
                    @if(!$isBundling && $cart->product && $cart->product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $cart->product->images->first()->image_url) }}" 
                             class="w-16 h-16 object-cover rounded-lg shadow-sm">
                    @elseif($isBundling && !empty($cart->bundling->barang_bundling))
                        <img src="{{ asset('storage/' . $cart->bundling->barang_bundling) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="font-semibold text-gray-800">{{ $itemName }}</div>
                            @if(!$isBundling && $cart->product)
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md inline-block mt-1">
                                    Unit: <strong>{{ Str::ucfirst($cart->unit) }}</strong>
                                </span>
                            @endif
                    </div>
                </div>
            </td>

            <td class="p-5">
                Rp{{ number_format($price,0,',','.') }}
            </td>

            <td class="p-5">
                <div class="flex items-center gap-2">
                    <button type="button" class="w-8 h-8 text-gray-600 hover:bg-gray-100 rounded-md" onclick="decreaseQuantity(this, {{ $cart->id }})">
                        <i class="bi bi-dash"></i>
                    </button>
                    <input type="number" value="{{ $cart->quantity }}" min="1" class="w-12 text-center bg-transparent focus:outline-none">
                    <button type="button" class="w-8 h-8 text-gray-600 hover:bg-gray-100 rounded-md" onclick="increaseQuantity(this, {{ $cart->id }})">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </td>

            <td class="p-5 font-semibold text-[#16782d]">
                <div class="bg-green-50 px-3 py-1 rounded-md inline-block" id="total-{{ $cart->id }}">
                    Rp{{ number_format($total,0,',','.') }}
                </div>
            </td>

            <td class="p-5">
                <form id="delete-form-{{ $cart->id }}" action="{{ route('carts.destroy', $cart->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="p-2 bg-red-50 rounded-full hover:bg-red-100 text-red-500" onclick="confirmDelete({{ $cart->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

<!-- Card Mobile -->
<div class="md:hidden space-y-6 mt-6 px-2">
@foreach($carts as $cart)
    @php
        $isBundling = $cart->bundling !== null;
        
        // GUNAKAN LOGIKA YANG SAMA DENGAN DESKTOP
        if ($isBundling) {
            $price = $cart->bundling->price;
            $itemName = $cart->bundling->nama_bundling;
        } else {
            // Mengambil harga yang sudah dikalkulasi saat simpan ke keranjang (bisa harga pcs/karton)
            $price = $cart->price; 
            $itemName = $cart->product->nama_produk;
        }

        $total = $price * $cart->quantity;
    @endphp
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 flex flex-col gap-4">
        <div class="flex items-center gap-4">
            @if(!$isBundling && $cart->product && $cart->product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $cart->product->images->first()->image_url) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
            @elseif($isBundling && !empty($cart->bundling->barang_bundling))
                <img src="{{ asset('storage/' . $cart->bundling->barang_bundling) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
            @else
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-image text-gray-400 text-2xl"></i>
                </div>
            @endif
            <div class="flex-1">
                <div class="font-semibold text-gray-800 truncate">{{ $itemName }}</div>
                @if(!$isBundling && $cart->product)
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md mt-1 inline-block">
                        Unit: <strong>{{ Str::ucfirst($cart->unit) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="flex justify-between items-center gap-4 text-sm text-gray-700">
            <div>
                <span class="font-semibold">Harga:</span> Rp{{ number_format($price,0,',','.') }}
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="w-8 h-8 text-gray-600 hover:bg-gray-100 rounded-md" onclick="decreaseQuantity(this, {{ $cart->id }})">
                    <i class="bi bi-dash"></i>
                </button>
                <input type="number" value="{{ $cart->quantity }}" min="1" class="w-12 text-center bg-transparent focus:outline-none" readonly>
                <button type="button" class="w-8 h-8 text-gray-600 hover:bg-gray-100 rounded-md" onclick="increaseQuantity(this, {{ $cart->id }})">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center gap-4">
            <div class="text-[#16782d] font-semibold text-lg">
                <span class="bg-green-50 px-3 py-1 rounded-md inline-block" id="total-mobile-{{ $cart->id }}">
                    Rp{{ number_format($total,0,',','.') }}
                </span>
            </div>
            <form id="delete-form-mobile-{{ $cart->id }}" action="{{ route('carts.destroy', $cart->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="flex items-center gap-1 p-2 bg-red-50 rounded-full hover:bg-red-100 text-red-500" onclick="confirmDelete({{ $cart->id }})">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
@endforeach
</div>

<!-- Ringkasan Belanja -->
<div class="mt-6 bg-white rounded-xl p-7 border border-gray-100 shadow-md max-w-md ml-auto">
    
    <!-- Judul Ringkasan -->
    <h1 class="text-2xl font-bold text-gray-800 mb-5">Ringkasan Pesanan</h1>

    <!-- Subtotal -->
    <div class="flex justify-between py-2 text-gray-600">
        <span class="font-medium">Subtotal</span>
        <span class="font-semibold text-gray-800" id="subtotal">Rp{{ number_format($subtotal,0,',','.') }}</span>
    </div>

    <!-- Total -->
    <div class="flex justify-between py-3 mt-2 border-t border-gray-200 text-lg font-bold">
        <span>Total</span>
        <span class="text-[#16782d]" id="totalSummary">Rp{{ number_format($subtotal,0,',','.') }}</span>
    </div>

    <!-- Tombol Aksi (Vertikal, atas-bawah) -->
    <div class="mt-6 flex flex-col gap-3">
        <a href="{{ route('public.orders.index') }}" 
           class="flex items-center justify-center bg-[#16782d] text-white px-6 py-3 rounded-lg font-medium hover:bg-[#0d5c20] transition-all duration-300">
            <i class="bi bi-check-circle mr-2 text-lg"></i> Beli Sekarang
        </a>
        <a href="{{ route('products') }}" 
           class="flex items-center justify-center border border-[#16782d] text-[#16782d] px-6 py-3 rounded-lg font-medium hover:bg-[#f0f9f2] transition-all duration-300">
            <i class="bi bi-arrow-left mr-2"></i> Lanjutkan Belanja
        </a>
    </div>
</div>


@endif
</div>

<script>
function updateCartQuantity(cartId, quantity) {
    fetch(`/carts/${cartId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`#total-${cartId}`).textContent = `Rp${data.total_formatted}`;
            document.querySelector('#subtotal').textContent = `Rp${data.subtotal_formatted}`;
            document.querySelector('#totalSummary').textContent = `Rp${data.subtotal_formatted}`;
        }
    })
    .catch(err => console.error(err));
}

function decreaseQuantity(button, cartId) {
    const input = button.parentNode.querySelector('input[type="number"]');
    let qty = parseInt(input.value);
    if (qty > 1) {
        qty -= 1;
        input.value = qty;
        updateCartQuantity(cartId, qty);
    }
}

function increaseQuantity(button, cartId) {
    const input = button.parentNode.querySelector('input[type="number"]');
    let qty = parseInt(input.value) + 1;
    input.value = qty;
    updateCartQuantity(cartId, qty);
}

function confirmDelete(cartId) {
    Swal.fire({
        title: 'Hapus item?',
        text: "Anda yakin ingin menghapus produk ini dari keranjang?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16782d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${cartId}`).submit();
        }
    });
}
</script>
@endsection
