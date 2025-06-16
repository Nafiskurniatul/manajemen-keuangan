<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan Neraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        td:first-child,
        th:first-child {
            text-align: left;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('assets/images/logomedia.png') }}" alt="Logo Kiri">
        <div class="title">
            <h2>Laporan Neraca</h2>
            <h4>{{ $startDate }} s/d {{ $endDate }}</h4>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2">Aktiva</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAsset = 0; @endphp
            @foreach($grouped['asset'] as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
            </tr>
            @php $totalAsset += $item['balance']; @endphp
            @endforeach
            <tr>
                <th>Total Aktiva</th>
                <th>Rp {{ number_format($totalAsset, 2, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2">Kewajiban & Ekuitas</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLiability = 0; $totalEquity = 0; @endphp
            @foreach($grouped['liability'] as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
            </tr>
            @php $totalLiability += $item['balance']; @endphp
            @endforeach

            @foreach($grouped['equity'] as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
            </tr>
            @php $totalEquity += $item['balance']; @endphp
            @endforeach

            <tr>
                <th>Total Kewajiban & Ekuitas</th>
                <th>Rp {{ number_format($totalLiability + $totalEquity, 2, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <script>
        window.print();
    </script>

</body>

</html>