@extends('layouts.app')

@section('title', 'Edit Data Alternatif')

@section('content')
<div class="space-y-6">

    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('alternatives.index') }}" class="w-10 h-10 rounded-xl bg-white/60 border border-white/60 flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-white hover:shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-orange-500">Edit Data Alternatif</h2>
                <p class="text-sm text-slate-500">Ubah nilai alternatif: <span class="font-semibold text-slate-700">{{ $alternative->name }}</span></p>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-3xl p-8">
        <form action="{{ route('alternatives.update', $alternative->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Alternatif</label>
                    <input type="text" name="name" value="{{ old('name', $alternative->name) }}" required 
                           class="w-full rounded-xl border border-white/60 bg-white/50 p-3 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition-all"
                           placeholder="Nama alternatif...">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                @foreach($criterias as $c)
                @php
                    $currentVal = $alternative->alternativeValues->where('criteria_id', $c->id)->first();
                @endphp
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <span class="inline-block px-2 py-0.5 rounded-md bg-gradient-to-r from-rose-100 to-amber-100 text-rose-600 text-xs font-bold mr-1">{{ $c->code }}</span>
                        {{ $c->name }}
                    </label>
                    <input type="number" step="0.01" name="values[{{ $c->id }}]" 
                           value="{{ old('values.' . $c->id, $currentVal ? $currentVal->value : '') }}"
                           required 
                           class="w-full rounded-xl border border-white/60 bg-white/50 p-3 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition-all"
                           placeholder="Masukkan nilai...">
                </div>
                @endforeach
            </div>
            
            <div class="flex items-center gap-4 border-t border-white/40 pt-6">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold hover:from-amber-600 hover:to-orange-600 shadow-md shadow-amber-500/30 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('alternatives.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-semibold hover:bg-white hover:shadow-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
