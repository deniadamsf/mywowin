<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wowin FOOD - Navbar Elegan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 70px;
        }

        .nav-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .animate-dropdown {
            transition: all 0.3s ease;
        }

        .user-menu {
            transform-origin: top right;
        }

        .logo-text {
            color: #7c1375;
            font-weight: 700;
        }

        .user-menu-button {
            transition: transform 0.2s;
        }

        .user-menu-button:hover {
            transform: scale(1.05);
        }

        .menu-item {
            transition: all 0.2s;
        }

        .menu-item:hover {
            transform: translateX(5px);
            background-color: #f0fdf4; /* Light green hover */
        }

        .main-content {
            height: 1000px;
            background: #f9fafb;
        }

        

        .btn {
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #34d399; /* Tailwind green-400 */
        }

        .search-bar {
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <!-- Navbar Fixed -->
    <nav class="bg-white nav-shadow p-4 flex justify-between items-center fixed top-0 left-0 w-full z-50">
        <div class="flex items-center">
            <button id="toggleSidebar" class="text-gray-700 hover:text-gray-900 focus:outline-none lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>              
            </button>
            <div class="hidden lg:flex items-center space-x-3 ml-4">
                <!-- Logo -->
                <div class="w-12 h-8 flex items-center justify-center">
                    <img src="{{ asset('images/wwn-cr.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl logo-text leading-none text-[#16782d]">My Wowin</span>
                    <span class="text-xs text-gray-500">Sahabat Hidangan Anda</span>
                </div>
            </div>
            
        </div>

      

        <div class="flex items-center space-x-4">
            <!-- Notification -->
            {{-- <button class="relative p-1 text-gray-700 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
            </button> --}}

            <!-- User Info -->
            <div class="hidden md:flex items-center">
                <div class="mr-2 text-right">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p>

                    <p class="text-xs text-gray-500">Super Admin</p>
                </div>
                <div class="relative">
                    <button type="button" id="user-menu-button" class="user-menu-button flex text-sm border-2 border-white rounded-full focus:outline-none focus:ring-2 focus:ring-red-500">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->foto_profile) }}" alt="user photo">
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div class="user-menu animate-dropdown absolute right-0 mt-2 w-56 bg-white divide-y divide-gray-100 rounded-lg shadow-xl hidden transform scale-95 opacity-0" id="user-dropdown">
                        <div class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p>

                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <ul class="py-1">
                            <li><a href="{{ route('superadmin.dashboard') }}" class="menu-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Dashboard
                            </a></li>
                            <li><a href="{{ route('superadmin.settings.index') }}" class="menu-item flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pengaturan
                            </a></li>
                            <li><a href="{{ route('logout') }}" class="menu-item flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Keluar
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- <button id="menuButton" class="p-1 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="2"></circle>
                    <circle cx="12" cy="5" r="2"></circle>
                    <circle cx="12" cy="19" r="2"></circle>
                </svg>
            </button> --}}
        </div>
    </nav>

    <script>
        // Toggle User Dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const dropdown = document.getElementById('user-dropdown');
            
            if (dropdown.classList.contains('hidden')) {
                // Show dropdown
                dropdown.classList.remove('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.add('scale-100', 'opacity-100');
                setTimeout(() => {
                    dropdown.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                // Hide dropdown
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const userButton = document.getElementById('user-menu-button');
            
            if (!dropdown.contains(event.target) && !userButton.contains(event.target) && !dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
            }
        });
        
        // Sidebar toggle functionality
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            // Disini Anda dapat menambahkan logika untuk toggle sidebar jika dibutuhkan
        });
    </script>
</body>
</html>
