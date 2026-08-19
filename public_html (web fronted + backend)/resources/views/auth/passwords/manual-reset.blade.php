<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Manual</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3); }
            50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.6); }
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
        .glow-animation { animation: glow 3s ease-in-out infinite; }
    </style>
</head>
<body>
    <!-- Background with gradient -->
    <div class="min-h-screen bg-gradient-to-br from-white via-green-100 to-green-50 relative overflow-hidden">
        
        <!-- Floating decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 w-20 h-20 bg-green-200/50 rounded-full float-animation"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-red-200/40 rounded-full float-animation" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-20 left-20 w-24 h-24 bg-green-300/60 rounded-full float-animation" style="animation-delay: -4s;"></div>
            <div class="absolute bottom-40 right-10 w-12 h-12 bg-red-300/30 rounded-full float-animation" style="animation-delay: -1s;"></div>
        </div>

        <!-- Main Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-md">
                
                <!-- Form Container -->
                <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-gray-200/50 glow-animation">
                    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-gray-100">
                            <!-- Logo container - siap untuk gambar -->
                            <img src="images/lg-h.png" alt="Logo" class="w-10 h-10 object-contain">
                            <!-- Atau untuk logo SVG bisa langsung paste di sini -->
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2 bg-gradient-to-r from-green-600 via-gray-700 to-red-600 bg-clip-text text-transparent">Reset Password</h2>
                        <p class="text-gray-600 text-sm">Masukkan informasi untuk mengatur ulang password Anda</p>
                    </div>

                    <!-- Error Messages -->
                    <div id="error-container" class="hidden mb-6 bg-red-50 border border-red-200 rounded-xl p-4 backdrop-blur-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-700 font-medium text-sm">Terdapat kesalahan:</span>
                        </div>
                        <ul id="error-list" class="text-red-600 text-sm space-y-1 ml-7">
                            <!-- Errors will be populated here -->
                        </ul>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('password.update.manual') }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Input Email -->
                <div class="space-y-2">
                    <label class="block text-gray-700 text-sm font-medium">Email Anda</label>

                    <!-- Wrapper relative untuk posisi ikon -->
                    <div class="relative">
                        <!-- Ikon SVG Email -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" 
                                viewBox="0 0 24 24" class="w-4 h-4 text-green-500">
                                <path fill-rule="evenodd" d="M17.834 6.166a8.25 8.25 0 1 0 0 11.668.75.75 0 0 1 1.06 1.06c-3.807 3.808-9.98 3.808-13.788 0-3.808-3.807-3.808-9.98 0-13.788 3.807-3.808 9.98-3.808 13.788 0A9.722 9.722 0 0 1 21.75 12c0 .975-.296 1.887-.809 2.571-.514.685-1.28 1.179-2.191 1.179-.904 0-1.666-.487-2.18-1.164a5.25 5.25 0 1 1-.82-6.26V8.25a.75.75 0 0 1 1.5 0V12c0 .682.208 1.27.509 1.671.3.401.659.579.991.579.332 0 .69-.178.991-.579.3-.4.509-.99.509-1.671a8.222 8.222 0 0 0-2.416-5.834ZM15.75 12a3.75 3.75 0 1 0-7.5 0 3.75 3.75 0 0 0 7.5 0Z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Input -->
                        <input type="email" name="email" required 
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 hover:border-green-300"
                            placeholder="Masukkan email Anda">
                    </div>

                    <!-- Small text warning -->
                    <small class="text-gray-500 text-xs flex items-center gap-1">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                        </svg>
                        Gunakan email yang terdaftar saat registrasi.
                    </small>
                </div>
                        <!-- Phone Number Field -->
                            <div class="space-y-2">
                                <label class="block text-gray-700 text-sm font-medium">Nomor HP Anda</label>

                                <!-- Wrapper relative untuk posisi ikon -->
                                <div class="relative">
                                    <!-- Ikon SVG Phone -->
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                                            class="w-4 h-4 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 
                                                2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 
                                                1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 
                                                1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 
                                                3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 
                                                0 2.25 4.5v2.25Z" />
                                        </svg>
                                    </div>

                                    <!-- Input -->
                                    <input type="text" name="no_hp" required 
                                        class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 hover:border-green-300"
                                        placeholder="Masukkan Nomor Anda">
                                </div>

                                <!-- Small text warning -->
                                <small class="text-gray-500 text-xs flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
                                    </svg>
                                    Gunakan Nomor yang terdaftar saat registrasi.
                                </small>
                            </div>
                        <!-- New Password Field -->
<div class="space-y-2">
    <label class="block text-gray-700 text-sm font-medium">Password Baru</label>
    <!-- Wrapper relative untuk posisi ikon -->
    <div class="relative">
        <!-- Ikon SVG Lock/Key -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                class="w-4 h-4 text-green-500">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 
                    2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 
                    2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <!-- Input -->
        <input type="password" id="newPassword" name="password" required 
            class="w-full pl-10 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 hover:border-green-300"
            placeholder="Masukkan password baru">

        <!-- Toggle Password Visibility -->
        <button type="button" onclick="togglePassword('newPassword', 'eyeIcon')" 
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <!-- Eye Icon (Show) -->
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" 
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </button>
    </div>
    <small class="text-gray-500 text-xs">Masukkan password baru.</small>
</div>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        // Change to eye-slash icon (hide)
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" 
                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 1-4.243-4.243m4.242 4.242L9.88 9.88" />
        `;
    } else {
        passwordInput.type = 'password';
        // Change back to eye icon (show)
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" 
                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
            <path stroke-linecap="round" stroke-linejoin="round" 
                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        `;
    }
}
</script>
                       <!-- Confirm Password Field -->
<div class="space-y-2">
    <label class="block text-gray-700 text-sm font-medium">Konfirmasi Password</label>
    <!-- Wrapper relative untuk posisi ikon -->
    <div class="relative">
        <!-- Ikon SVG Lock/Key -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                class="w-4 h-4 text-green-500">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 
                    2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 
                    2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <!-- Input -->
        <input type="password" id="confirmPassword" name="password_confirmation" required 
            class="w-full pl-10 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 hover:border-green-300"
            placeholder="Konfirmasi password baru">

        <!-- Toggle Password Visibility -->
        <button type="button" onclick="togglePassword('confirmPassword', 'eyeIconConfirm')" 
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <!-- Eye Icon (Show) -->
            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" 
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </button>
    </div>
    <small class="text-gray-500 text-xs">Konfirmasi password baru.</small>
</div>

                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-white shadow-lg"
                            style="box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.3), 0 10px 10px -5px rgba(34, 197, 94, 0.04);">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Ubah Password
                            </span>
                        </button>

                        <!-- Back to Home Button -->
                        <button type="button" onclick="window.location.href='{{ route('login') }}'" 
                            class="w-full bg-white border border-green-500 text-green-600 font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 mt-4"
                            style="box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3), 0 10px 10px -5px rgba(16, 185, 129, 0.04);">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Kembali
                            </span>
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-500 text-xs">Pastikan password Anda aman dan mudah diingat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });

            // Mock error handling for demonstration
            // In real Laravel app, this would be handled by Blade template
            const errors = @json($errors->all() ?? []);
            if (errors && errors.length > 0) {
                const errorContainer = document.getElementById('error-container');
                const errorList = document.getElementById('error-list');
                
                errorContainer.classList.remove('hidden');
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            }
        });
    </script>
</body>
</html>