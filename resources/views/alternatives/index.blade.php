@extends('layouts.app')

@section('title', 'Data Alternatif')

@section('content')
<div class="space-y-6">

    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-500">Data Alternatif</h2>
                <p class="text-sm text-slate-500">Kelola data alternatif dan nilai kriteria kelapa sawit</p>
            </div>
        </div>
    </div>


    <!-- Import Excel Panel -->
    <div class="glass-panel rounded-3xl p-6 mb-6 border border-emerald-200/50">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-400/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-700">Import Data via Excel / CSV</h3>
                <p class="text-xs text-slate-500">Upload file Excel sekaligus untuk 500 data atau lebih</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('alternatives.template') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Template Excel
                </a>
            </div>
        </div>

        <form action="{{ route('alternatives.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Pilih File Excel / CSV</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv"
                           class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-white/60 border border-white/60 rounded-xl p-1.5 cursor-pointer transition">
                    @error('file')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold hover:from-emerald-600 hover:to-teal-700 shadow-md shadow-emerald-500/30 transition-all whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Sekarang
                </button>
            </div>
            <p class="text-xs text-slate-400 mt-2">
                💡 Format kolom: <code class="bg-slate-100 px-1 rounded">nama_alternatif</code>, 
                @foreach($criterias as $c)
                    <code class="bg-slate-100 px-1 rounded">{{ strtolower($c->code) }}</code>{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </p>
        </form>
    </div>

    <!-- Form Tambah Data Manual -->
    <div class="glass-panel rounded-3xl p-6 mb-8">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-sm font-semibold text-slate-500">✏️ Atau tambah data manual satu per satu:</span>
        </div>
        <form action="{{ route('alternatives.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="col-span-full">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Alternatif</label>
                    <input type="text" name="name" required class="w-full border-white/60 rounded-xl shadow-sm focus:border-rose-500 focus:ring-rose-500 bg-white/50 p-2.5 outline-none transition" placeholder="Masukkan nama alternatif...">
                </div>
                
                @foreach($criterias as $c)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">{{ $c->code }} - {{ $c->name }}</label>
                    <input type="number" step="0.01" name="values[{{ $c->id }}]" required class="w-full border-white/60 rounded-xl shadow-sm focus:border-rose-500 focus:ring-rose-500 bg-white/50 p-2.5 outline-none transition" placeholder="Nilai...">
                </div>
                @endforeach
            </div>
            
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 text-white font-semibold hover:from-rose-600 hover:to-rose-700 shadow-md shadow-rose-500/30 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Data
            </button>
        </form>
    </div>


    <!-- Tabel Data Alternatif -->
    <div class="glass-panel rounded-3xl p-6">
        <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2">
            <span class="text-amber-500">📄</span> Data Alternatif Saat Ini
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white/50 text-slate-600 font-bold border-b border-white/60">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">Nama Alternatif</th>
                        @foreach($criterias as $c)
                            <th class="px-4 py-3 whitespace-nowrap text-center">
                                {{ $c->code }}
                            </th>
                        @endforeach
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/30">
                    @forelse($alternatives as $alt)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $alt->name }}</td>
                        @foreach($criterias as $c)
                            @php
                                $val = $alt->alternativeValues->where('criteria_id', $c->id)->first();
                            @endphp
                            <td class="px-4 py-3 text-center text-slate-600">
                                {{ $val ? number_format($val->value, 2, ',', '.') : '-' }}
                            </td>
                        @endforeach
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('alternatives.edit', $alt->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                
                                <form id="delete-{{ $alt->id }}" action="{{ route('alternatives.destroy', $alt->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" @click="confirmDelete('delete-{{ $alt->id }}')"
                                   class="w-8 h-8 rounded-lg bg-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $criterias->count() + 2 }}" class="px-4 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="font-medium">Belum ada data alternatif. Tambahkan data di atas.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
