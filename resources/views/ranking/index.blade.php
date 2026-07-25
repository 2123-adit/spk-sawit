@extends('layouts.app')

@section('title', 'Hasil Rangking')

@section('content')
<div class="space-y-6">

    <div class="mb-8 flex justify-between items-start">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-orange-500">Hasil Rangking</h2>
                <p class="text-sm text-slate-500">Perangkingan akhir berdasarkan nilai Yi metode MOORA</p>
            </div>
        </div>
        
        @if($result)
        <a href="{{ route('report.pdf') }}" target="_blank" class="px-5 py-2.5 rounded-xl font-semibold text-sm bg-gradient-to-r from-slate-700 to-slate-900 text-white hover:from-slate-800 hover:to-black shadow-md transition-all duration-200 flex items-center gap-2 mt-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Cetak Laporan PDF
        </a>
        @endif
    </div>

    @if(!$result)
        <div class="bg-white p-8 rounded-xl border border-slate-100 text-center shadow-sm">
            <p class="text-slate-500">Data belum lengkap untuk melakukan perangkingan.</p>
        </div>
    @else

    <!-- Tabel Hasil Rangking -->
    <div class="glass-panel rounded-3xl p-6 mb-8">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2 text-lg">
            <span class="text-indigo-500">📊</span> Tabel Hasil Rangking
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white/50 text-slate-600 font-bold border-b border-white/60">
                    <tr>
                        <th class="px-4 py-3 text-center w-24">Ranking</th>
                        <th class="px-4 py-3">Nama Alternatif</th>
                        <th class="px-4 py-3 text-center">Nilai (Yi)</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($result['preferences'] as $index => $pref)
                    <tr class="hover:bg-slate-50 transition-colors {{ $pref['rank'] === 1 ? 'bg-amber-50/50' : '' }}">
                        <td class="px-4 py-3 text-center font-bold {{ $pref['rank'] === 1 ? 'text-amber-500 text-lg' : 'text-slate-600' }}">
                            {{ $pref['rank'] }}
                        </td>
                        <td class="px-4 py-3 font-medium {{ $pref['rank'] === 1 ? 'text-amber-700' : 'text-slate-700' }}">
                            {{ $pref['alternative']->name }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono font-semibold {{ $pref['rank'] === 1 ? 'text-amber-600' : 'text-slate-600' }}">
                            {{ number_format($pref['yi'], 4, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold">
                            @if($pref['rank'] === 1)
                                <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">Terbaik</span>
                            @else
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-600">Alternatif {{ $pref['rank'] - 1 }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Visualisasi Garis -->
    <div class="glass-panel rounded-3xl p-6">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2 text-lg">
            <span class="text-rose-400">📈</span> Visualisasi Hasil Rangking
        </h3>
        <p class="text-sm text-slate-500 mb-6">Visualisasi Nilai Yi Setiap Alternatif</p>
        
        <div class="h-[400px]">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    @endif
</div>

@if($result)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('lineChart').getContext('2d');
        
        // Use all preferences for the line chart
        const labels = {!! json_encode(collect($result['preferences'])->map(fn($p) => $p['alternative']->name)->values()) !!};
        const data = {!! json_encode(collect($result['preferences'])->map(fn($p) => $p['yi'])->values()) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor Yi',
                    data: data,
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3b82f6',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    });
</script>
@endif

@endsection
