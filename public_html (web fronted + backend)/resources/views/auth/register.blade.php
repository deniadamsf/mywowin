<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Member - PT. Wowin Purnomo Putera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js"></script>
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        .form-input:focus {
            border-color: #16782d;
            box-shadow: 0 0 0 3px rgba(22, 120, 45, 0.15);
        }
        .input-with-icon {
            transition: all 0.3s ease;
        }
        .input-with-icon:focus-within {
            transform: translateY(-1px);
        }
        .animated-circle {
            animation: float 8s ease-in-out infinite;
        }
        .animated-circle-reverse {
            animation: float-reverse 7s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        @keyframes float-reverse {
            0% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
            100% { transform: translateY(0px); }
        }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316782d' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen bg-pattern">
    <!-- Decorative Circles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Top Left Circles -->
        <div class="absolute top-0 left-0">
            <div class="w-64 h-64 rounded-full bg-green-600 opacity-5 absolute -top-20 -left-20"></div>
            <div class="w-48 h-48 rounded-full bg-green-500 opacity-10 absolute top-10 left-10 animated-circle"></div>
        </div>
        
        <!-- Bottom Right Circles -->
        <div class="absolute bottom-0 right-0">
            <div class="w-96 h-96 rounded-full bg-green-600 opacity-5 absolute -bottom-40 -right-40"></div>
            <div class="w-64 h-64 rounded-full bg-green-500 opacity-10 absolute bottom-20 right-20 animated-circle-reverse"></div>
        </div>
        
        <!-- Scattered Small Circles -->
        <div class="w-16 h-16 rounded-full bg-green-400 opacity-10 absolute top-1/4 right-1/3 animated-circle"></div>
        <div class="w-20 h-20 rounded-full bg-green-400 opacity-5 absolute bottom-1/3 left-1/4 animated-circle-reverse"></div>
        <div class="w-12 h-12 rounded-full bg-green-500 opacity-10 absolute top-2/3 right-1/4 animated-circle"></div>
    </div>

    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Header dengan Logo dan Tulisan Berjalan -->
<header class="bg-[#16782d] shadow-md py-3 md:py-4 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 md:px-6 flex items-center justify-between relative">
        <img src="{{ asset('images/wwn-cr.webp') }}" alt="Logo" class="h-8 w-8 md:h-10 md:w-10 object-contain rounded-full shadow-sm bg-white p-1 z-10 flex-shrink-0">
        
        <div class="flex-1 min-w-0 px-2 text-center space-y-0.5 md:space-y-1">
            <div class="text-white text-sm md:text-lg font-bold tracking-wide drop-shadow-sm animate-loop-right whitespace-nowrap">
               • MyWowin • MyWowin • MyWowin •
            </div>
            <div class="text-green-100 text-[10px] md:text-sm italic font-medium drop-shadow-sm animate-loop-left whitespace-nowrap">
               • Sahabat Hidangan Anda • Sahabat Hidangan Anda • 
            </div>
        </div>

        <img src="{{ asset('images/wwn-cr.webp') }}" alt="Logo Kanan" class="h-8 w-8 md:h-10 md:w-10 object-contain rounded-full shadow-sm bg-white p-1 z-10 flex-shrink-0">
    </div>
</header>

<style>
/* Animasi looping dari kanan */
@keyframes loopRight {
    0% { transform: translateX(100%); opacity: 0; }
    15% { transform: translateX(0); opacity: 1; }
    50% { transform: translateX(0); opacity: 1; } 
    85% { transform: translateX(-100%); opacity: 0; } /* Keluar ke kiri agar smooth */
    100% { transform: translateX(-100%); opacity: 0; }
}

/* Animasi looping dari kiri */
@keyframes loopLeft {
    0% { transform: translateX(-100%); opacity: 0; }
    15% { transform: translateX(0); opacity: 1; }
    50% { transform: translateX(0); opacity: 1; } 
    85% { transform: translateX(100%); opacity: 0; } /* Keluar ke kanan agar smooth */
    100% { transform: translateX(100%); opacity: 0; }
}

.animate-loop-right {
    animation: loopRight 8s ease-in-out infinite; /* Durasi diperlambat sedikit agar enak dibaca */
}

.animate-loop-left {
    animation: loopLeft 8s ease-in-out infinite;
    animation-delay: 0.5s;
}
</style>
        <!-- Tambahkan animasi CSS -->
        <style>
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes marquee-slow {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            animation: marquee 10s linear infinite;
        }
        .animate-marquee-slow {
            display: inline-block;
            animation: marquee-slow 16s linear infinite;
        }
        </style>
        

        <!-- Main Content -->
        <main class="flex-grow flex items-center justify-center py-12 px-4">
            <div class="w-full max-w-4xl flex flex-col items-center">
               
                
                <!-- Registration Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full">
                    <div class="p-8">
                         <!-- Centered Logo Above Form -->
                        <div class="mb-8 text-center">
                            <img src="{{ asset('images/wwn-cr.webp') }}" alt="Logo" class="h-40 translate-y-6 mx-auto mb-4">
                            <h1 class="text-3xl font-bold text-gray-800">Daftar Member</h1>
                            <p class="text-gray-600 mt-2">Bergabunglah dengan PT. Wowin Purnomo Putera</p>
                        </div>
                        <form action="{{ route('auth.register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Username -->
                                <div>
                                    <label for="username" class="text-sm font-medium text-gray-700 block mb-1.5">Username</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="username" 
                                            id="username" 
                                            value="{{ old('username') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Masukkan username"
                                        />
                                    </div>
                                    @error('username')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="text-sm font-medium text-gray-700 block mb-1.5">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="email" 
                                            name="email" 
                                            id="email" 
                                            value="{{ old('email') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="contoh@email.com"
                                        />
                                    </div>
                                    @error('email')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="text-sm font-medium text-gray-700 block mb-1.5">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="nama_lengkap" 
                                            id="nama_lengkap" 
                                            value="{{ old('nama_lengkap') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Nama lengkap Anda"
                                        />
                                    </div>
                                    @error('nama_lengkap')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="text-sm font-medium text-gray-700 block mb-1.5">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="password" 
                                            name="password" 
                                            id="password" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Minimal 8 karakter"
                                        />
                                    </div>
                                    @error('password')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nama Toko -->
                                <div>
                                    <label for="nama_toko" class="text-sm font-medium text-gray-700 block mb-1.5">Nama Toko</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="nama_toko" 
                                            id="nama_toko" 
                                            value="{{ old('nama_toko') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Nama toko Anda"
                                        />
                                    </div>
                                    @error('nama_toko')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="text-sm font-medium text-gray-700 block mb-1.5">Alamat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text"
                                            name="alamat" 
                                            id="alamat" 
                                            value="{{ old('alamat') }}"
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Alamat lengkap"
                                        />
                                    </div>
                                    @error('alamat')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nomor HP -->
                                <div>
                                    <label for="no_hp" class="text-sm font-medium text-gray-700 block mb-1.5">Nomor HP</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="number" 
                                            name="no_hp" 
                                            id="no_hp" 
                                            value="{{ old('no_hp') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="08xxxxxxxxxx"
                                        />
                                    </div>
                                    @error('no_hp')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nama Sales -->
                                <div>
                                    <label for="nama_sales" class="text-sm font-medium text-gray-700 block mb-1.5">Nama Sales</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="nama_sales" 
                                            id="nama_sales" 
                                            value="{{ old('nama_sales') }}" 
                                            required 
                                            class="form-input w-full pl-10 p-2.5 border border-gray-300 rounded-lg focus:outline-none text-gray-700 bg-white"
                                            placeholder="Nama sales Anda"
                                        />
                                    </div>
                                    @error('nama_sales')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Cabang Terdekat-->
                                <div>
                                    <label for="kantor_cabang" class="block text-sm font-medium text-gray-700 mb-1">Kantor Cabang</label>
                                    <select 
                                        name="kantor_cabang" 
                                        id="kantor_cabang" 
                                        onchange="toggleMembershipForm()" 
                                        class="w-full p-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white text-gray-700"
                                        required
                                    >
                                        <option value="" disabled selected>Pilih Kantor Cabang</option>
                                        <option value="Trenggalek" {{ old('kantor_cabang') == 'Trenggalek' ? 'selected' : '' }}>Trenggalek</option>
                                        <option value="Kediri" {{ old('kantor_cabang') == 'Kediri' ? 'selected' : '' }}>Kediri</option>
                                        <option value="Madiun" {{ old('kantor_cabang') == 'Madiun' ? 'selected' : '' }}>Madiun</option>
                                        <option value="Solo" {{ old('kantor_cabang') == 'Solo' ? 'selected' : '' }}>Solo</option>
                                        <option value="Jogja" {{ old('kantor_cabang') == 'Jogja' ? 'selected' : '' }}>Jogja</option>
                                        <option value="Cirebon" {{ old('kantor_cabang') == 'Cirebon' ? 'selected' : '' }}>Cirebon</option>
                                        <option value="Kudus" {{ old('kantor_cabang') == 'Kudus' ? 'selected' : '' }}>Kudus</option>
                                        <option value="Bogor" {{ old('kantor_cabang') == 'Bogor' ? 'selected' : '' }}>Bogor</option>
                                        <option value="Serang" {{ old('kantor_cabang') == 'Serang' ? 'selected' : '' }}>Serang</option>
                                    </select>
                                    @error('kantor_cabang')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>

                            <!-- Foto Profil -->
                            <div class="mt-2">
                                <label for="foto_profile" class="text-sm font-medium text-gray-700 block mb-1.5">Foto Profil</label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center bg-gray-50 transition-all hover:bg-gray-50/80" x-data="{ fileName: '' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-1">Klik untuk unggah atau drag & drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (Max. 2MB)</p>
                                    <input 
                                        type="file" 
                                        name="foto_profile" 
                                        id="foto_profile" 
                                        required 
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        @change="fileName = $event.target.files[0].name"
                                    />
                                    <div class="text-sm text-blue-600 mt-2" x-text="fileName"></div>
                                </div>
                                @error('foto_profile')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full py-3 px-6 bg-[#16782d] hover:bg-[#0f5a22] text-white font-medium rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none flex items-center justify-center">
                                    <span>Daftar Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center text-sm mt-4">
                                <p class="text-gray-600">
                                    Sudah memiliki akun? 
                                    <a href="{{ route('login') }}" class="text-[#16782d] font-medium hover:underline">Masuk</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Benefits Section -->
                <div class="mt-8 bg-white/90 backdrop-blur-sm rounded-xl shadow-md p-6 w-full">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Keuntungan Menjadi Member</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start space-x-3 p-4 rounded-lg bg-green-50 border border-green-100">
                            <div class="bg-[#16782d] p-2 rounded-full text-white flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Keamanan Terjamin</h4>
                                <p class="text-sm text-gray-600 mt-1">Sistem pembayaran dan transaksi yang aman dan terpercaya</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 rounded-lg bg-green-50 border border-green-100">
                            <div class="bg-[#16782d] p-2 rounded-full text-white flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Harga Kompetitif</h4>
                                <p class="text-sm text-gray-600 mt-1">Dapatkan penawaran harga terbaik untuk produk berkualitas</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 rounded-lg bg-green-50 border border-green-100">
                            <div class="bg-[#16782d] p-2 rounded-full text-white flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Layanan Cepat</h4>
                                <p class="text-sm text-gray-600 mt-1">Proses pengiriman dan pelayanan yang cepat dan efisien</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/90 backdrop-blur-sm border-t border-gray-200 py-6 mt-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <img src="{{ asset('images/lg-h.png') }}" alt="Logo" class="h-8 mr-3">
                        <span class="text-gray-500 text-sm">© {{ date('Y') }} PT. Wowin Purnomo Putera. All rights reserved.</span>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-500 hover:text-[#16782d]">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1