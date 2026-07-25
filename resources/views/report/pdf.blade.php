<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SPK Kelapa Sawit (MOORA)</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">LAPORAN SISTEM PENDUKUNG KEPUTUSAN</h1>
        <p class="subtitle">Penentuan Harga Kelapa Sawit (Metode MOORA)</p>
    </div>

    @if($result)
        <div class="section-title">1. Hasil Perangkingan (Nilai Preferensi)</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">Peringkat</th>
                    <th>Alternatif</th>
                    <th class="text-right">Nilai Preferensi (Yi)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['preferences'] as $pref)
                <tr>
                    <td class="text-center {{ $pref['rank'] == 1 ? 'font-bold' : '' }}">{{ $pref['rank'] }}</td>
                    <td class="{{ $pref['rank'] == 1 ? 'font-bold' : '' }}">{{ $pref['alternative']->name }}</td>
                    <td class="text-right">{{ number_format($pref['yi'], 4, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-title">2. Matriks Keputusan</div>
        <table>
            <thead>
                <tr>
                    <th>Alt</th>
                    @foreach($result['criterias'] as $c)
                        <th class="text-center">{{ $c->code }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($result['alternatives'] as $alt)
                <tr>
                    <td>{{ $alt->name }}</td>
                    @foreach($result['criterias'] as $c)
                        <td class="text-right">{{ number_format($result['matrix'][$alt->id][$c->id] ?? 0, 2, ',', '.') }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <p style="margin-top: 30px; text-align: right;">
            Dicetak pada: {{ date('d-m-Y H:i:s') }}
        </p>
    @else
        <p>Data belum lengkap untuk menghasilkan laporan.</p>
    @endif

</body>
</html>
