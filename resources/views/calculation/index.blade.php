@extends('layouts.app')

@section('title', 'Perhitungan MOORA')

@section('content')
<div class="space-y-6">

    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Perhitungan MOORA</h2>
                <p class="text-sm text-slate-500">Proses normalisasi, pembobotan dan optimasi multi-kriteria</p>
            </div>
        </div>
    </div>

    @if(!$result)
        <div class="bg-white p-8 rounded-xl border border-slate-100 text-center shadow-sm">
            <p class="text-slate-500">Data belum lengkap untuk melakukan perhitungan.</p>
        </div>
    @else

    <div x-data="{ activeTab: 'keputusan' }" class="space-y-6">
        <!-- Tab Navigation -->
        <div class="flex flex-wrap gap-3 glass-panel p-2 rounded-2xl mb-2">
            <button @click="activeTab = 'keputusan'" 
                    :class="activeTab === 'keputusan' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md' : 'hover:bg-white/50 text-slate-600'"
                    class="px-6 py-2.5 rounded-xl font-semibold transition-all text-sm flex items-center gap-2">
                📊 Data Alternatif
            </button>
            <button @click="activeTab = 'bobot'" 
                    :class="activeTab === 'bobot' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-md' : 'hover:bg-white/50 text-slate-600'"
                    class="px-6 py-2.5 rounded-xl font-semibold transition-all text-sm flex items-center gap-2">
                ⚖️ Bobot Kriteria
            </button>
            <button @click="activeTab = 'normalisasi'" 
                    :class="activeTab === 'normalisasi' ? 'bg-gradient-to-r from-indigo-500 to-blue-500 text-white shadow-md' : 'hover:bg-white/50 text-slate-600'"
                    class="px-6 py-2.5 rounded-xl font-semibold transition-all text-sm flex items-center gap-2">
                📐 Matriks Normalisasi
            </button>
        </div>

    <!-- Matriks Keputusan -->
    <div x-show="activeTab === 'keputusan'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         class="glass-panel rounded-3xl p-6 mb-8" 
         style="display: none;">
        <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2 text-lg">
            <span class="text-emerald-500">📊</span> Data Alternatif terhadap Kriteria
        </h3>
        <p class="text-sm text-slate-500 mb-6 bg-white/50 p-3 rounded-xl border border-white/60">Data ini menunjukkan nilai dari setiap alternatif (kelapa sawit) terhadap kriteria yang telah ditentukan.</p>
        
        <div class="overflow-auto max-h-[500px] rounded-xl border border-slate-100 shadow-inner">
            <table class="w-full text-left text-sm text-slate-600 relative">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3">Alt</th>
                        @foreach($result['criterias'] as $c)
                            <th class="px-4 py-3 text-center">{{ $c->code }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($result['alternatives'] as $alt)
                    <tr class="hover:bg-white/50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ $alt->name }}</td>
                        @foreach($result['criterias'] as $c)
                            <td class="px-4 py-3 text-center">{{ number_format($result['matrix'][$alt->id][$c->id] ?? 0, 2, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bobot Tiap Kriteria -->
    <div x-show="activeTab === 'bobot'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         class="glass-panel rounded-3xl p-6 mb-8" 
         style="display: none;">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2 text-lg">
            <span class="text-amber-500">⚖️</span> Bobot Tiap Kriteria
        </h3>
        
        <div class="overflow-auto rounded-xl border border-slate-100 shadow-inner">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Kriteria</th>
                        <th class="px-4 py-3 text-center">Bobot</th>
                        <th class="px-4 py-3 text-center">Jenis Atribut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($result['criterias'] as $c)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ $c->code }}</td>
                        <td class="px-4 py-3">{{ $c->name }}</td>
                        <td class="px-4 py-3 text-center">{{ $c->weight }}</td>
                        <td class="px-4 py-3 text-center text-xs">
                            @if($c->type === 'benefit')
                                Benefit (+)
                            @else
                                Cost (-)
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Matriks Normalisasi -->
    <div x-show="activeTab === 'normalisasi'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         class="glass-panel rounded-3xl p-6" 
         style="display: none;">
        <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2 text-lg">
            <span class="text-indigo-500">📐</span> Matriks Normalisasi
        </h3>
        <p class="text-sm text-slate-500 mb-6 bg-white/50 p-3 rounded-xl border border-white/60">Matriks yang telah dinormalisasi menggunakan persamaan akar jumlah kuadrat pada setiap kriteria.</p>
        
        <div class="overflow-auto max-h-[500px] rounded-xl border border-slate-100 shadow-inner">
            <table class="w-full text-left text-sm text-slate-600 relative">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3">Alt</th>
                        @foreach($result['criterias'] as $c)
                            <th class="px-4 py-3 text-center">{{ $c->code }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($result['alternatives'] as $alt)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ $alt->name }}</td>
                        @foreach($result['criterias'] as $c)
                            <td class="px-4 py-3 text-center">{{ number_format($result['normalizedMatrix'][$alt->id][$c->id] ?? 0, 4, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </div>

    @endif
</div>
@endsection
