<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SPK Kelapa Sawit') }} | MOORA Method</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .sidebar-active {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.3), 0 4px 6px -2px rgba(225, 29, 72, 0.15);
        }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-rose-500 selection:text-white relative"
    x-data="{
        sidebarOpen: false,
        toast: { show: false, message: '', type: 'success' },
        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => this.toast.show = false, 3500);
        },
        deleteModal: { show: false, formId: null },
        confirmDelete(formId) { this.deleteModal = { show: true, formId }; },
        executeDelete() {
            if (this.deleteModal.formId) {
                document.getElementById(this.deleteModal.formId).submit();
            }
            this.deleteModal.show = false;
        }
    }"
    x-init="
        @if(session('success')) showToast('{{ session('success') }}', 'success') @endif
        @if(session('error')) showToast('{{ session('error') }}', 'error') @endif
    ">

    <!-- Toast Notification -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-8 opacity-0"
         class="fixed bottom-6 right-6 z-[999] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border border-white/40 backdrop-blur-xl"
         :class="toast.type === 'success' ? 'bg-emerald-500/90 text-white' : 'bg-rose-500/90 text-white'"
         style="display: none;">
        <template x-if="toast.type === 'success'">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </template>
        <template x-if="toast.type === 'error'">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </template>
        <span x-text="toast.message" class="font-semibold text-sm"></span>
        <button @click="toast.show = false" class="ml-2 opacity-70 hover:opacity-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModal.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[998] flex items-center justify-center p-4"
         style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteModal.show = false"></div>
        <!-- Modal Box -->
        <div x-show="deleteModal.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative glass-panel rounded-3xl p-8 w-full max-w-sm shadow-2xl text-center">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-slate-500 text-sm mb-8">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button @click="deleteModal.show = false" class="flex-1 py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button @click="executeDelete()" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 text-white font-semibold hover:from-rose-600 hover:to-rose-700 shadow-md shadow-rose-500/30 transition-all">
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>

    <!-- Decorative Background Orbs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-rose-200/40 blur-[100px]"></div>
        <div class="absolute top-[20%] -right-[10%] w-[30%] h-[50%] rounded-full bg-amber-200/40 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[50%] h-[30%] rounded-full bg-orange-200/30 blur-[100px]"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 transition-all duration-300 ease-in-out lg:static lg:translate-x-0 glass-panel border-r border-white/60 flex flex-col">
            <div class="flex flex-col items-center justify-center p-8 border-b border-white/40">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 bg-gradient-to-br from-amber-400 to-rose-500 text-white shadow-lg shadow-rose-500/30">
                    <!-- Kelapa Sawit (Oil Palm Tree) Icon -->
                    <svg class="w-11 h-11" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Trunk -->
                        <rect x="21.5" y="26" width="5" height="16" rx="2.5" fill="white" opacity="0.9"/>
                        <!-- Trunk texture lines -->
                        <line x1="22" y1="29" x2="27" y2="29" stroke="rgba(255,150,50,0.4)" stroke-width="1"/>
                        <line x1="22" y1="32" x2="27" y2="32" stroke="rgba(255,150,50,0.4)" stroke-width="1"/>
                        <line x1="22" y1="35" x2="27" y2="35" stroke="rgba(255,150,50,0.4)" stroke-width="1"/>
                        <!-- Fruit Bunch (TBS) -->
                        <ellipse cx="24" cy="29" rx="5" ry="4" fill="white" opacity="0.25"/>
                        <circle cx="22" cy="28" r="2.2" fill="white" opacity="0.8"/>
                        <circle cx="26" cy="28" r="2.2" fill="white" opacity="0.8"/>
                        <circle cx="24" cy="30.5" r="2.2" fill="white" opacity="0.8"/>
                        <!-- Center Top Frond -->
                        <path d="M24 26 C23 20 21 14 20 8" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M20 8 C18 10 22 12 24 10" stroke="white" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <!-- Left Fronds -->
                        <path d="M24 26 C18 22 10 20 4 22" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M4 22 C6 20 8 24 10 22" stroke="white" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <path d="M24 26 C20 19 14 13 8 10" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M8 10 C8 13 12 13 12 11" stroke="white" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <path d="M24 26 C20 23 12 25 6 30" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                        <!-- Right Fronds -->
                        <path d="M24 26 C30 22 38 20 44 22" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                        <path d="M44 22 C42 20 40 24 38 22" stroke="white" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <path d="M24 26 C28 19 34 13 40 10" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M40 10 C40 13 36 13 36 11" stroke="white" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6"/>
                        <path d="M24 26 C28 23 36 25 42 30" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-amber-600 tracking-wide">SPK MOORA</span>
            </div>

            <div class="px-6 py-6 flex-1 overflow-y-auto">
                <div class="mb-8 p-4 rounded-2xl bg-white/50 border border-white/60 shadow-sm">
                    <p class="text-sm font-bold text-slate-800 mb-1">Hai, admin! 👋</p>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Sistem Pendukung Keputusan pemilihan & perhitungan kelapa sawit.</p>
                </div>
                
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 pl-2">
                    Main Menu
                </p>
                
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-white hover:text-rose-600 hover:shadow-sm' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('criterias.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('criterias.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-white hover:text-rose-600 hover:shadow-sm' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Kriteria
                    </a>
                    <a href="{{ route('alternatives.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('alternatives.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-white hover:text-rose-600 hover:shadow-sm' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Data Alternatif
                    </a>
                    <a href="{{ route('calculation.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('calculation.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-white hover:text-rose-600 hover:shadow-sm' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Perhitungan MOORA
                    </a>
                    <a href="{{ route('ranking.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('ranking.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-white hover:text-rose-600 hover:shadow-sm' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Hasil Rangking
                    </a>
                </nav>
            </div>
            
            <div class="mt-auto p-6 border-t border-white/40">
                <button class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/50 border border-white/60 text-slate-500 text-sm font-bold hover:bg-white hover:text-rose-500 hover:shadow-sm transition-all w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout System
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <header class="h-20 flex items-center justify-between px-6 lg:px-12 z-10 glass-panel border-b border-white/60 m-4 rounded-2xl">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-rose-500 p-2 rounded-xl hover:bg-rose-50 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <div class="hidden lg:flex items-center gap-4 text-slate-400">
                    <div class="bg-white/50 px-4 py-2 rounded-full border border-white/60 shadow-sm text-sm font-medium">
                        Sistem Penentu Harga TBS
                    </div>
                </div>
                <div class="ml-auto text-sm font-bold text-slate-500 flex items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white hover:shadow-md border border-white/60 transition cursor-pointer text-rose-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path></svg>
                        Share
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-auto px-6 pb-12 lg:px-12 z-0 relative scroll-smooth">
                <div class="max-w-7xl mx-auto pt-4 animate-in fade-in slide-in-from-bottom-4 duration-700 ease-out">
                    @yield('content')
                </div>
            </div>
        </main>
        
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"></div>
    </div>
</body>
</html>
