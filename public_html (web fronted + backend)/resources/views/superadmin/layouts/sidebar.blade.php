<aside id="sidebar" class="bg-white w-64 h-[calc(100vh-4rem)] fixed top-16 left-0 transition-transform -translate-x-full lg:translate-x-0 shadow-lg border-r border-gray-200 overflow-y-auto z-40">
    <div class="py-4">
        <!-- Logo and Brand -->
        <div class="px-6 mb-4">
            <div class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-md bg-purple-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="white" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Super Admin</h2>
            </div>
        </div>

        <!-- Divider -->
        <div class="h-px bg-gray-200 mb-4"></div>

        <ul class="space-y-1 px-3">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('superadmin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg group transition-all duration-200 
                          {{ request()->is('superadmin/dashboard') 
                              ? 'bg-purple-50 text-purple-700 font-medium' 
                              : 'text-gray-700 hover:bg-gray-100' }}">
                    
                    <div class="{{ request()->is('superadmin/dashboard') ? 'text-purple-600' : 'text-gray-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </li>

            {{-- User Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/users*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/users*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/users*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">User Management</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.users.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/users') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.users.master-member') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/users/master-member') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Member</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('superadmin.users.create') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/users/create') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Tambah User</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- IDENTITAS CABANG --}}
            <li x-data="{ open: {{ request()->is('superadmin/branch_settings*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/branch_settings*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/branch_settings*') ? 'text-purple-600' : 'text-gray-500' }}">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>

                        </div>                        
                        <span class="text-sm font-medium">Identitas PT</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.branch-settings.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/komentars') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master PT</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- Comment Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/komentars*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/komentars*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/komentars*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>                        
                        <span class="text-sm font-medium">Kelola Komentar</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.komentars.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/komentars') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Komentar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            {{-- Live Chat Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/chats*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/chats*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/chats*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>                        
                        <span class="text-sm font-medium">Live Chat Member</span>
                    </div>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.chats.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/chats*') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <span>Pesan Masuk</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Reward Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/rewards*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/rewards*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/rewards*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                            </svg>
                        </div>                        
                        <span class="text-sm font-medium">Kelola Reward</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.rewards.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/rewards') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Reward</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- orders Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/orders*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/orders*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/orders*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" />
                            </svg>
                        </div>                        
                        <span class="text-sm font-medium">Kelola Order</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.orders.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/orders') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Order</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- promo Management --}}
            <li x-data="{ open: {{ request()->is('superadmin/bundlings*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/bundlings*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/bundlings*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.99 14.993 6-6m6 3.001c0 1.268-.63 2.39-1.593 3.069a3.746 3.746 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043 3.745 3.745 0 0 1-3.068 1.593c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.746 3.746 0 0 1-1.043-3.297 3.746 3.746 0 0 1-1.593-3.068c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.297 3.745 3.745 0 0 1 3.296-1.042 3.745 3.745 0 0 1 3.068-1.594c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.297 3.746 3.746 0 0 1 1.593 3.068ZM9.74 9.743h.008v.007H9.74v-.007Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>

                        </div>                        
                        <span class="text-sm font-medium">Kelola Promo</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.bundlings.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/bundlings') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Promo</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- Products --}}
            <li x-data="{ open: {{ request()->is('superadmin/products*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/products*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/products*') ? 'text-purple-600' : 'text-gray-500' }}">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                           </svg>

                        </div>                        
                        <span class="text-sm font-medium">Kelola Produk</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.products.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/products') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Produk</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
             <!-- Display Management -->
            <li x-data="{ open: {{ request()->is('superadmin/ilustrations*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/ilustrations*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/ilustrations*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Kelola Tampilan</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.ilustrations.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/ilustrations') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Tampilan Login</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.heros.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/heros') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Tampilan Hero</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </li>
            
            {{-- category --}}
            <li x-data="{ open: {{ request()->is('superadmin/categories*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/categories*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/categories*') ? 'text-purple-600' : 'text-gray-500' }}">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                            </svg>

                        </div>                        
                        <span class="text-sm font-medium">Kelola Category</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.categories.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/categories') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Category</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- rekap --}}
            <li x-data="{ open: {{ request()->is('superadmin/rekap*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/rekap*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/rekap*') ? 'text-purple-600' : 'text-gray-500' }}">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                            </svg>

                        </div>                        
                        <span class="text-sm font-medium">Rekap Retur</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.rekap.retur') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/rekap') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Retur</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>



            {{-- artikel --}}
            <li x-data="{ open: {{ request()->is('superadmin/artikels*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/artikels*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/artikels*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>

                        </div>                        
                        <span class="text-sm font-medium">Kelola Artikel</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.artikels.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/artikels') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                
                                <span>Master Artikel</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Ulasan & Rating Pelanggan --}}
            <li x-data="{ open: {{ request()->is('superadmin/reviews*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/reviews*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/reviews*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                            </svg>
                        </div>                        
                        <span class="text-sm font-medium">Ulasan & Rating</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="2" 
                         stroke="currentColor" 
                         class="w-3.5 h-3.5 transition-transform duration-200" 
                         :class="open ? 'rotate-90' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 ml-6 pl-3 border-l border-gray-200">
                    <ul class="space-y-1 py-1">
                        <li>
                            <a href="{{ route('superadmin.reviews.index') }}" 
                               class="flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-all duration-200 
                                      {{ request()->is('superadmin/reviews*') ? 'text-purple-700 bg-purple-50 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <span>Pusat Moderasi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Metode Pembayaran --}}
            <li>
                <a href="{{ route('superadmin.payment_methods.index') }}" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 {{ request()->is('superadmin/payment-methods*') ? 'bg-purple-50 text-purple-700 font-medium' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="{{ request()->is('superadmin/payment-methods*') ? 'text-purple-600' : 'text-gray-500' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Metode Pembayaran</span>
                    </div>
                </a>
            </li>
          
       <div class="flex flex-col h-full">
    <!-- Scrollable menu content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Divider -->
        <div class="h-px bg-gray-200 my-3"></div>
    </div>

    {{-- <!-- User Profile at bottom -->
    <div class="border-t border-gray-200 bg-white py-3 px-4 sticky bottom-0 cursor-pointer" id="profileToggle">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5 text-purple-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 11-7.5 
                          0 3.75 3.75 0 017.5 
                          0zM4.501 20.118a7.5 7.5 0 
                          0114.998 0A17.933 17.933 0 
                          0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div> --}}

{{-- <!-- Modal -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-end justify-center z-50">
    <div class="bg-white rounded-t-2xl shadow-lg w-full max-w-sm p-5 animate-slide-up">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Menu Profil</h2>
        <ul class="space-y-2">
            <!-- Settings item -->
            <li>
                <a href="settings"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg group transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <div class="text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 
                                  1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 
                                  1.075.124l1.217-.456a1.125 1.125 0 
                                  011.37.49l1.296 2.247a1.125 1.125 0 
                                  01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 
                                  6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 
                                  1.43l-1.298 2.247a1.125 1.125 0 
                                  01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 
                                  6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 
                                  1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 
                                  0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 
                                  6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 
                                  1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 
                                  01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 
                                  6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 
                                  1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 
                                  011.37-.491l1.216.456c.356.133.751.072 
                                  1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Pengaturan</span>
                </a>
            </li>
            <!-- Logout item -->
            <li>
                <form method="GET" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg group transition-all duration-200 text-gray-700 hover:bg-red-50 hover:text-red-700">
                        <div class="text-gray-500 group-hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 
                                      2.25 0 00-2.25 2.25v13.5A2.25 
                                      2.25 0 007.5 21h6a2.25 
                                      2.25 0 002.25-2.25V15M12 
                                      9l-3 3m0 0l3 3m-3-3h12.75"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div> --}}
<div class="border-t border-gray-200 bg-white py-3 px-4 sticky bottom-0 cursor-pointer z-50" id="profileToggle">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden border border-purple-200">
                <span class="text-purple-700 font-bold text-xs">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside> <div id="profileModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[9999] p-4">
    <div class="absolute inset-0" onclick="closeModal()"></div>
    
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xs p-5 relative animate-slide-up">
        <h2 class="text-lg font-bold mb-4 text-gray-800">Menu Profil</h2>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('superadmin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-purple-700 hover:bg-purple-100 transition-all group">
                    <i class="fas fa-cog" style="color: #7e22ce;"></i> 
                    <span class="text-sm font-semibold" style="color: #7e22ce;">Pengaturan</span>
                </a>
            </li>
            <li>
                <form method="GET" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all text-left">
                        <i class="fas fa-sign-out-alt text-gray-400"></i>
                        <span class="text-sm font-medium">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
        <button onclick="closeModal()" class="w-full mt-4 py-2 text-xs text-gray-400 hover:text-gray-600">Tutup</button>
    </div>
</div>

<style>
    /* Animasi muncul dari bawah */
    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
</style>

<script>
   const profileToggle = document.getElementById('profileToggle');
    const profileModal = document.getElementById('profileModal');

    function openModal() {
        profileModal.classList.remove('hidden');
        profileModal.classList.add('flex');
    }

    function closeModal() {
        profileModal.classList.add('hidden');
        profileModal.classList.remove('flex');
    }

    profileToggle.addEventListener('click', openModal);

    // Tutup jika klik area hitam (overlay)
    profileModal.addEventListener('click', function(e) {
        if (e.target === profileModal) closeModal();
    });
</script>


</aside>
