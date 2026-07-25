@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 via-amber-600 to-orange-500">Dashboard SPK MOORA</h2>
        </div>
        <p class="text-slate-500 mt-2 pl-16">Sistem Pendukung Keputusan untuk Perhitungan Harga TBS Kelapa Sawit</p>
    </div>

    <!-- Info Box -->
    <div class="glass-panel rounded-2xl p-4 mb-6 border border-blue-100/30">
        <details class="group">
            <summary class="flex items-center gap-2 font-semibold text-rose-600 cursor-pointer list-none">
                <div class="w-5 h-5 rounded-md bg-gradient-to-br from-rose-400 to-amber-500 flex items-center justify-center text-white text-[10px] font-bold">M</div>
                Apa itu MOORA?
                <span class="transition group-open:rotate-180 ml-auto text-slate-400">
                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20"><path d="M6 9l6 6 6-6"></path></svg>
                </span>
            </summary>
            <div class="text-sm text-slate-600 mt-3 pl-7">
                Metode <strong>Multi-Objective Optimization on the Basis of Ratio Analysis (MOORA)</strong> adalah sistem multiobjektif yang mengoptimalkan dua atau lebih atribut yang saling bertentangan secara bersamaan untuk menghasilkan keputusan terbaik.
            </div>
        </details>
    </div>

    <!-- 3 Summary Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-panel rounded-3xl p-6 flex flex-col items-center justify-center text-center">
            <h4 class="text-slate-500 font-semibold mb-2 flex items-center gap-2">
                <span class="text-amber-500">🥥</span> Jumlah Data
            </h4>
            <span class="text-3xl font-bold text-emerald-600">{{ $alternativeCount }}</span>
        </div>
        
        <div class="glass-panel rounded-3xl p-6 flex flex-col items-center justify-center text-center">
            <h4 class="text-slate-500 font-semibold mb-2 flex items-center gap-2">
                <span class="text-blue-400">📋</span> Jumlah Kriteria
            </h4>
            <span class="text-3xl font-bold text-blue-600">{{ $criteriaCount }}</span>
        </div>

        <div class="glass-panel rounded-3xl p-6 flex flex-col items-center justify-center text-center">
            <h4 class="text-slate-500 font-semibold mb-2 flex items-center gap-2">
                <span class="text-yellow-500">🏆</span> Terbaik
            </h4>
            <span class="text-2xl font-bold text-rose-500">{{ $bestAlternative ?? '-' }}</span>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="glass-panel rounded-3xl p-6 mb-8">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2">
            <span class="text-indigo-400">📈</span> 5 Data Terbaik Berdasarkan Skor Yi
        </h3>
        <div class="h-[400px]">
            <canvas id="rankingChart"></canvas>
        </div>
    </div>

    <!-- Table Ranking Lengkap -->
    <div class="glass-panel rounded-3xl p-6">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2">
            <span class="text-orange-400">📄</span> Tabel Hasil Ranking Lengkap
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white/50 text-slate-600 font-bold border-b border-white/60">
                    <tr>
                        <th class="px-4 py-3">Alt</th>
                        @if($result)
                            @foreach($result['criterias'] as $c)
                                <th class="px-4 py-3 text-center">{{ $c->code }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-center">Yi</th>
                            <th class="px-4 py-3 text-center">Ranking</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($result)
                        @foreach($result['preferences'] as $pref)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $pref['alternative']->name }}</td>
                            @foreach($result['criterias'] as $c)
                                <td class="px-4 py-3 text-center">{{ number_format($result['normalizedMatrix'][$pref['alternative']->id][$c->id] ?? 0, 4) }}</td>
                            @endforeach
                            <td class="px-4 py-3 text-center font-bold text-slate-700">{{ number_format($pref['yi'], 4) }}</td>
                            <td class="px-4 py-3 text-center">{{ $pref['rank'] }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center py-4">Belum ada data</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

@if($result)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('rankingChart').getContext('2d');
        
        const labels = {!! json_encode(collect($top5)->map(fn($p) => $p['alternative']->name)->values()) !!};
        const data = {!! json_encode(collect($top5)->map(fn($p) => $p['yi'])->values()) !!};
        
        // Match the colors from the screenshot: Teal, SkyBlue, Red, Blue, Pink
        const backgroundColors = [
            '#20B2AA', // Teal
            '#87CEFA', // SkyBlue
            '#FF4500', // Red/Orange
            '#1E90FF', // Blue
            '#FFB6C1', // Pink
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor Yi',
                    data: data,
                    backgroundColor: backgroundColors,
                    barThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Hide legend to match screenshot
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            maxRotation: 90,
                            minRotation: 90
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
