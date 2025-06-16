@extends('layouts.app')
@section('title', 'Laporan Neraca')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3 fw-semibold">Laporan Neraca</h5>

        <form method="GET" class="row mb-4">
            <div class="col-md-4">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
        <a href="{{ route('neraca.print', request()->only(['start_date', 'end_date'])) }}" target="_blank" class="btn btn btn-primary mb-3">
            <i class="bi bi-printer"></i> Cetak
        </a>

        @if(!empty($grouped['asset']) || !empty($grouped['liability']) || !empty($grouped['equity']))
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th colspan="2">Aktiva</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalAsset = 0; @endphp
                    @foreach($grouped['asset'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
                    </tr>
                    @php $totalAsset += $item['balance']; @endphp
                    @endforeach
                    <tr class="fw-bold bg-light">
                        <td>Total Aktiva</td>
                        <td class="text-end">Rp {{ number_format($totalAsset, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th colspan="2">Kewajiban & Ekuitas</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalLiability = 0; $totalEquity = 0; @endphp

                    @foreach($grouped['liability'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
                    </tr>
                    @php $totalLiability += $item['balance']; @endphp
                    @endforeach

                    @foreach($grouped['equity'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['balance'], 2, ',', '.') }}</td>
                    </tr>
                    @php $totalEquity += $item['balance']; @endphp
                    @endforeach

                    <tr class="fw-bold bg-light">
                        <td>Total Kewajiban & Ekuitas</td>
                        <td class="text-end">Rp {{ number_format($totalLiability + $totalEquity, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection