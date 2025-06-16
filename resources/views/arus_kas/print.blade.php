<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Arus Kas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

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

        h2, h4 {
            margin: 0;
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
    <div class="section">
        <!-- Header dengan logo kiri dan kanan -->
        <div class="header">
            <img src="{{ asset('assets/images/logomedia.png') }}" alt="Logo Kiri">
            <div class="title">
                <h2>Laporan Arus Kas</h2>
                <h4>{{ $startDate }} s/d {{ $endDate }}</h4>
            </div>
        </div>

        <!-- Penerimaan Kas -->
        <h5>Penerimaan Kas</h5>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 25%;">Akun</th>
                    <th style="width: 35%;">Keterangan</th>
                    <th style="width: 20%;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penerimaan as $trx)
                <tr>
                    <td>{{ $trx->date }}</td>
                    <td>{{ $trx->account->name }}</td>
                    <td>{{ $trx->keterangan }}</td>
                    <td>{{ number_format($trx->debit, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
                <tr class="total">
                    <td colspan="3">Total Masuk</td>
                    <td>{{ number_format($totalMasuk, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Pengeluaran Kas -->
        <h5>Pengeluaran Kas</h5>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 25%;">Akun</th>
                    <th style="width: 35%;">Keterangan</th>
                    <th style="width: 20%;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $trx)
                <tr>
                    <td>{{ $trx->date }}</td>
                    <td>{{ $trx->account->name }}</td>
                    <td>{{ $trx->keterangan }}</td>
                    <td>{{ number_format($trx->credit, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
                <tr class="total">
                    <td colspan="3">Total Keluar</td>
                    <td>{{ number_format($totalKeluar, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin-top: 30px;">Saldo Kas Akhir: Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h4>
    </div>
</body>

</html>
