@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Dashboard')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8fafc;
    }
    .gradient-bg {
        background: linear-gradient(135deg, #6b46c1 0%, #805ad5 100%);
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-2">
    <!-- Header Section -->
    <div class="gradient-bg text-white p-6 rounded-xl shadow-lg mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang Super Admin</h1>
                <p class="text-purple-100 opacity-90 text-lg">Sistem Manajemen My Wowin</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-white bg-opacity-20 px-4 py-2 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span>Super Admin</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 card-hover transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <h3 class="text-2xl font-bold">{{ number_format($totalPengguna) }}</h3>
                    <p class="text-xs text-gray-400">Admin: {{ number_format($totalAdmin) }} | Member: {{ number_format($totalMember) }}</p>
                </div>
            </div>
        </div>
        

        <div class="bg-white rounded-xl shadow-md p-6 card-hover transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan</p>
                    <h3 class="text-2xl font-bold">{{ number_format($totalOrders) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pendapatan</p>
                    <h3 class="text-2xl font-bold">Rp{{ number_format($pendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Aktivitas Hari Ini</p>
                    <h3 class="text-2xl font-bold">{{ $aktivitasHariIni }}</h3>
                    <p class="text-xs text-gray-400">
                        Order: {{ $orderToday }} | Login: {{ $loginToday }} | Register: {{ $registerToday }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Aktivitas Terkini</h2>
       <button onclick="toggleModal('modalActivities')" class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
    Lihat Semua
</button>
    </div>
    
    <div class="space-y-4">
        @foreach ($recentActivities as $activity)
            <div class="flex items-start">
                <div class="bg-{{ $activity['type'] == 'user' ? 'purple' : ($activity['type'] == 'order' ? 'purple' : 'blue') }}-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['type'] == 'user' ? 'purple' : ($activity['type'] == 'order' ? 'purple' : 'blue') }}-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium">{{ $activity['message'] }}</p>
                    <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Calendar Widget -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            Kalender
        </h2>
        
        <!-- Month Header -->
        <div class="flex items-center justify-between mb-4">
            <button id="prevMonth" class="p-1 hover:bg-purple-50 rounded transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h3 id="monthYear" class="text-lg font-semibold text-purple-700"></h3>
            <button id="nextMonth" class="p-1 hover:bg-purple-50 rounded transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Days of Week -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center text-xs font-medium text-purple-600 py-2">MIN</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">SEN</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">SEL</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">RAB</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">KAM</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">JUM</div>
            <div class="text-center text-xs font-medium text-purple-600 py-2">SAB</div>
        </div>
        
        <!-- Calendar Grid -->
        <div id="calendarGrid" class="grid grid-cols-7 gap-1">
            <!-- Days will be generated by JavaScript -->
        </div>
        
        <!-- Today info -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Hari ini:</span>
                <span id="todayInfo" class="text-purple-700 font-medium"></span>
            </div>
        </div>
    </div>

    <script>
        class LiveCalendar {
            constructor() {
                this.currentDate = new Date();
                this.today = new Date();
                this.monthNames = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                this.dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                
                this.init();
            }
            
            init() {
                this.render();
                this.bindEvents();
                this.updateTime();
                
                // Update every minute
                setInterval(() => {
                    this.today = new Date();
                    this.render();
                    this.updateTime();
                }, 60000);
            }
            
            bindEvents() {
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                    this.render();
                });
                
                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                    this.render();
                });
            }
            
            getDaysInMonth() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay();
                
                const days = [];
                
                // Previous month's trailing days
                const prevMonth = new Date(year, month - 1, 0);
                for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                    days.push({
                        day: prevMonth.getDate() - i,
                        isCurrentMonth: false,
                        isPrevMonth: true
                    });
                }
                
                // Current month days
                for (let day = 1; day <= daysInMonth; day++) {
                    days.push({
                        day: day,
                        isCurrentMonth: true,
                        isPrevMonth: false
                    });
                }
                
                // Next month's leading days
                const totalCells = 42; // 6 rows × 7 days
                const remainingCells = totalCells - days.length;
                for (let day = 1; day <= remainingCells; day++) {
                    days.push({
                        day: day,
                        isCurrentMonth: false,
                        isPrevMonth: false
                    });
                }
                
                return days;
            }
            
            isToday(dayObj) {
                if (!dayObj.isCurrentMonth) return false;
                return this.today.getDate() === dayObj.day && 
                       this.today.getMonth() === this.currentDate.getMonth() && 
                       this.today.getFullYear() === this.currentDate.getFullYear();
            }
            
            render() {
                // Update month/year header
                document.getElementById('monthYear').textContent = 
                    `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                
                // Generate calendar grid
                const days = this.getDaysInMonth();
                const grid = document.getElementById('calendarGrid');
                grid.innerHTML = '';
                
                days.forEach(dayObj => {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'h-8 flex items-center justify-center text-sm rounded cursor-pointer transition-colors';
                    
                    if (dayObj.isCurrentMonth) {
                        dayElement.className += ' text-gray-700 hover:bg-purple-50';
                        if (this.isToday(dayObj)) {
                            dayElement.className = 'h-8 flex items-center justify-center text-sm bg-purple-700 text-white rounded font-medium';
                        }
                    } else {
                        dayElement.className += ' text-gray-400';
                    }
                    
                    dayElement.textContent = dayObj.day;
                    grid.appendChild(dayElement);
                });
            }
            
            updateTime() {
                const todayFormatted = this.today.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('todayInfo').textContent = todayFormatted;
            }
        }
        
        // Initialize calendar when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new LiveCalendar();
        });
    </script>


    </div>
</div>
<div id="modalActivities" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="toggleModal('modalActivities')"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 overflow-hidden">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h3 class="text-xl font-bold text-gray-800">Seluruh Aktivitas Terkini</h3>
                <button onclick="toggleModal('modalActivities')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="max-height-[60vh] overflow-y-auto pr-2" style="max-height: 400px;">
                <div class="space-y-4">
                    @foreach ($allActivities as $act)
                    <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-100 last:border-0">
                        <div class="bg-{{ $act['type'] == 'user' ? 'purple' : 'blue' }}-100 p-2 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $act['type'] == 'user' ? 'purple' : 'blue' }}-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $act['message'] }}</p>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-purple-600 font-semibold">{{ $act['time'] }}</p>
                                <p class="text-xs text-gray-400">{{ $act['date'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 text-right">
                <button onclick="toggleModal('modalActivities')" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Mencegah scroll pada background
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Mengembalikan scroll
    }
}

// Menutup modal jika user menekan tombol ESC
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        const modal = document.getElementById('modalActivities');
        if (!modal.classList.contains('hidden')) {
            toggleModal('modalActivities');
        }
    }
});
</script>

@endsection