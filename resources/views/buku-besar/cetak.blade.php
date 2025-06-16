<!DOCTYPE html>
<html>

<head>
    <title>Laporan Buku Besar</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h4 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Laporan Buku Besar</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>

    @foreach($groupedTransactions as $accountName => $transactions)
    <h4>{{ $accountName }}</h4>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th style="text-align:right">Debit</th>
                <th style="text-align:right">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}</td>
                <td>{{ $t->reference }}</td>
                <td style="text-align:right">Rp {{ number_format($t->debit, 2, ',', '.') }}</td>
                <td style="text-align:right">Rp {{ number_format($t->credit, 2, ',', '.') }}</td>
            </tr>
            @endforeach

            @php
            $totalDebit = $transactions->sum('debit');
            $totalCredit = $transactions->sum('credit');
            @endphp

            <tr>
                <td colspan="2" style="text-align:right; font-weight:bold;">Total</td>
                <td style="text-align:right; font-weight:bold;">Rp {{ number_format($totalDebit, 2, ',', '.') }}</td>
                <td style="text-align:right; font-weight:bold;">Rp {{ number_format($totalCredit, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @endforeach
</body>

</html>