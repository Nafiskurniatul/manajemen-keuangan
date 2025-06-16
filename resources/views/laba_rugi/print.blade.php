<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Laba Rugi</title>
    <style>
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .header img {
            width: 80px;
        }

        .header .title {
            flex: 1;
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        h2,
        h4 {
            text-align: center;
            margin-bottom: 0;
        }

        .section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #333;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('assets/images/logomedia.png') }}" alt="Logo Kiri">
        <div class="title">
            <h2>Laporan Laba Rugi</h2>
            <h4>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h4>
        </div>
    </div>


    @foreach ($data as $kategori => $transaksis)
    @if (count($transaksis))
    <div class="section">
        <strong>{{ $kategori }}</strong>
        <table>
            <thead>
                <tr>
                    <th>Nama Akun</th>
                    <th class="right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $trx)
                <tr>
                    <td>{{ $trx->account->name }}</td>
                    <td class="right">
                        {{ number_format($kategori === 'Pendapatan' ? $trx->credit : $trx->debit, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="total">Total {{ $kategori }}</td>
                    <td class="right total">Rp {{ number_format($totals[$kategori], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    @endforeach

    <div class="section">
        <h3 style="text-align: center; margin-top: 30px;">
            Laba Bersih: Rp {{ number_format($laba_bersih, 0, ',', '.') }}
        </h3>
    </div>
</body>

</html>