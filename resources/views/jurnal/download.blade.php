<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jurnal PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f2f2f2; }
        td.text-end { text-align: right; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Journal Entry</h3>
    <p><strong>Reference ID:</strong> {{ $journal->reference_id }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($journal->date)->format('d-m-Y') }}</p>
    <p><strong>Keterangan:</strong> {{ $journal->description }}</p>

    <table>
        <thead>
            <tr>
                <th>Akun</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $item)
            <tr>
                <td>{{ $item->account->name ?? '-' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td class="text-end">Rp {{ number_format($item->debit, 2, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->credit, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
