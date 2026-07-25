@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')
<div class="space-y-6">
    
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-500">Data Kriteria</h2>
                <p class="text-sm text-slate-500">Kelola kriteria dan bobot yang digunakan dalam metode MOORA</p>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-3xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                <span class="text-rose-500">📌</span> Tabel Data Kriteria
            </h3>
            <span class="text-xs font-bold text-slate-400 bg-white/50 px-3 py-1.5 rounded-full border border-white/60">Total Bobot: {{ number_format($criterias->sum('weight'), 2) }}</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white/50 text-slate-600 font-bold border-b border-white/60">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Kriteria</th>
                        <th class="px-4 py-3 text-center">Bobot (0-1)</th>
                        <th class="px-4 py-3 text-center">Jenis</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/30">
                    @forelse($criterias as $c)
                    <tr class="hover:bg-white/40 transition-colors" x-data="{ editing: false }">
                        <td class="px-4 py-3">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-gradient-to-r from-rose-100 to-amber-100 text-rose-600 font-bold text-xs">{{ $c->code }}</span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $c->name }}</td>
                        
                        {{-- View Mode --}}
                        <td class="px-4 py-3 text-center" x-show="!editing">
                            <span class="font-mono font-bold text-indigo-600">{{ $c->weight }}</span>
                            <span class="text-xs text-slate-400 ml-1">({{ number_format($c->weight * 100, 0) }}%)</span>
                        </td>
                        <td class="px-4 py-3 text-center" x-show="!editing">
                            @if($c->type === 'benefit')
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Benefit (+)</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-600 text-xs font-bold">Cost (-)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center" x-show="!editing">
                            <button @click="editing = true" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center mx-auto" title="Edit Bobot">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </td>

                        {{-- Edit Mode (inline) --}}
                        <form action="{{ route('criterias.update', $c->id) }}" method="POST" x-show="editing" class="contents" style="display:none;">
                            @csrf
                            @method('PUT')
                            <td class="px-4 py-2 text-center">
                                <input type="number" name="weight" value="{{ $c->weight }}" step="0.01" min="0" max="1"
                                       class="w-24 text-center rounded-xl border border-amber-300 bg-amber-50/50 p-2 text-sm font-mono font-bold text-indigo-700 focus:outline-none focus:ring-2 focus:ring-amber-400/40">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <select name="type" class="rounded-xl border border-amber-300 bg-amber-50/50 p-2 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400/40">
                                    <option value="benefit" {{ $c->type === 'benefit' ? 'selected' : '' }}>Benefit (+)</option>
                                    <option value="cost" {{ $c->type === 'cost' ? 'selected' : '' }}>Cost (-)</option>
                                </select>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center" title="Simpan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button type="button" @click="editing = false" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition-all flex items-center justify-center" title="Batal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </form>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400 font-medium">Belum ada data kriteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 border-t border-white/40 pt-5 space-y-2">
            <p class="text-sm font-semibold text-slate-600">Keterangan:</p>
            <ul class="text-sm text-slate-500 list-disc list-inside space-y-1 pl-2">
                <li><span class="font-bold text-emerald-600">Benefit (+):</span> Semakin besar nilainya, semakin baik (menguntungkan).</li>
                <li><span class="font-bold text-rose-500">Cost (-):</span> Semakin kecil nilainya, semakin baik (efisien).</li>
            </ul>
            <p class="text-xs text-slate-400 mt-3">Klik tombol <span class="font-semibold text-amber-500">✏️ Edit</span> pada baris kriteria untuk mengubah bobot dan jenisnya secara langsung.</p>
        </div>
    </div>

</div>
@endsection
