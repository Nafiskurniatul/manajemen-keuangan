<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Other Incoming Transaction</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td, th { border: 1px solid black; padding: 5px; text-align: left; }
        .no-border td { border: none; }
        .center { text-align: center; }
        .right { text-align: right; }
    </style>
</head>
<body>

    <table class="no-border">
        <tr>
            <td style="width: 60%;">
                <strong>Fluffytwist</strong><br>
                Telp: 6283165580630<br>
                Email: nafisafaizah21043@gmail.com
            </td>
            <td class="center">
                <h3>Other Incoming Transaction</h3>
                <p>Penerimaan Lain</p>
            </td>
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td><strong>Receipt To</strong>: Bank --- {{ $transaction->account_name ?? 'Akun Bank' }}</td>
            <td><strong>Date</strong>: {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td><strong>Payer</strong>: - </td>
            <td><strong>Currency</strong>: IDR</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Account No.</th>
                <th>Account Name</th>
                <th>Memo / Reference</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->account_number }}</td>
                <td>{{ $transaction->account_name }}</td>
                <td>{{ $transaction->description }}</td>
                <td class="right">{{ number_format($transaction->amount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Say</strong> : {{ ucwords(\Terbilang::make($transaction->amount, ' rupiah')) }}</p>

    <br><br>

    <table class="no-border">
        <tr>
            <td class="center">Prepared By,</td>
            <td class="center">Approved By,</td>
            <td class="center">Paid By,</td>
            <td class="center">Received By,</td>
        </tr>
        <tr>
            <td class="center"><br><br>__________________</td>
            <td class="center"><br><br>__________________</td>
            <td class="center"><br><br>__________________</td>
            <td class="center"><br><br>__________________</td>
        </tr>
        <tr>
            <td class="center">Date: ____________</td>
            <td class="center">Date: ____________</td>
            <td class="center">Date: ____________</td>
            <td class="center">Date: ____________</td>
        </tr>
    </table>

</body>
</html>
