<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
</head>
<body class="min-h-screen bg-cover bg-center" style="background-image: url({{ asset('images/lg-c.webp') }});">
    <div class="flex flex-col md:flex-row min-h-screen w-full overflow-hidden">
        <!-- Gambar Kiri - Hidden on mobile, visible on md screens and up -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center">
            @if ($ilustration && $ilustration->gambar_login)
                <div 
                    class="h-[80%] w-[80%] bg-no-repeat bg-center bg-contain md:translate-y-24 md:translate-x-14" 
                    style="background-image: url('{{ asset('storage/' . $ilustration->gambar_login) }}');">
                </div>
            @else
                <div 
                    class="h-[80%] w-[80%] bg-no-repeat bg-center bg-contain md:translate-y-24 md:translate-x-14" 
                    style="background-image: url('{{ asset('images/lg-a.png') }}');">
                </div>
            @endif
        </div>

        <!-- Form - Full width on mobile, half width on md screens and up -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-4 py-10 md:py-0">
            <div class="w-full max-w-md bg-[#16782d] p-6 md:p-8 rounded-lg shadow-lg bg-opacity-90 backdrop-blur-sm md:translate-y-14 translate-y-60">

                <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6 text-white text-center">Login Member</h2>
                <h3 class="text-center text-white text-sm md:text-base">Jika belum memiliki akun</h3>
                <a href="{{ route('auth.register') }}" class="text-[#fff301] hover:underline block text-center mb-4 md:mb-6 text-sm md:text-base">Daftar di sini</a>

                <form action="{{ route('auth.login.post') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-3 md:gap-4">
                        <div>
                            <label for="username" class="font-bold text-white block mb-1 md:mb-2 text-sm md:text-base">Username</label>
                            <input 
                                type="text" 
                                name="username" 
                                id="username" 
                                value="{{ old('username') }}" 
                                required 
                                class="w-full text-black p-2 md:p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Username"
                            />
                            @error('username')
                                <div class="text-red-700 font-semibold text-sm md:text-base mt-1">{{ $message }}</div>

                            @enderror
                        </div>
                        <div class="mb-2 md:mb-4">
                            <label for="password" class="font-bold text-white block mb-1 md:mb-2 text-sm md:text-base">Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    value="{{ old('password') }}" 
                                    required 
                                    class="w-full p-2 md:p-3 pr-10 border text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Password"
                                />
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 px-3 flex items-center focus:outline-none">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-red-700 font-semibold text-sm md:text-base mt-1">{{ $message }}</div>
                            @enderror
                        </div>                        
                    </div>

                    <button type="submit" class="w-full font-bold bg-[#fff301] text-[#16782d] py-2 md:py-3 rounded-lg hover:bg-[#16782d] hover:text-white transition text-sm md:text-base">Login</button>
                <!-- Link Lupa Password -->
                <div class="text-center mt-3">
                    <a href="{{ route('password.reset.manual') }}" class="text-white text-sm hover:underline">
                        Lupa Password?
                    </a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
    
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.959 9.959 0 012.45-4.033m1.855-1.516A9.953 9.953 0 0112 5
                        c4.477 0 8.268 2.943 9.542 7a9.961 9.961 0 01-4.507 5.169M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />`;
            } else {
                passwordInput.type = "password";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                          c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</body>
</html>