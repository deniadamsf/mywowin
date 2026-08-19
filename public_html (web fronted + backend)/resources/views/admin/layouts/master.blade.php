<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @yield('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        #loadingScreen {
            transition: opacity 0.3s ease;
        }
        body.loading {
        overflow: hidden;
    }
    [x-cloak] {
        display: none !important;
    }
    </style>

     <!-- Konfigurasi Tailwind untuk Font Poppins -->
     <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
 <!-- Loader -->
 <div id="loadingScreen" class="fixed inset-0 z-50 bg-white flex items-center justify-center">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-[#16782d] border-opacity-50"></div>
</div>
    @include('admin.layouts.navbar')

    <div class="flex">
        @include('admin.layouts.sidebar')

        <!-- Konten Utama -->
        <main class="flex-1 p-6 lg:ml-64">
            @yield('content')
            
            @include('admin.layouts.footer')
        </main>

        
    </div>

   

    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // Toggle Dropdown
        document.getElementById('menuButton').addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
    </script>
<script>
    document.body.classList.add('loading');
    window.addEventListener('load', () => {
        const loader = document.getElementById('loadingScreen');
        if (loader) {
            loader.style.opacity = 0;
            document.body.classList.remove('loading');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });
</script>

</body>
</html>
