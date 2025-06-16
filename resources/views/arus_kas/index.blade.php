@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <h4 class="m-4">Laporan Arus Kas</h4>
        <div class="card-body">

            <!-- Form Filter Tanggal -->
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>
            <div class="col-md-2">
                <a href="{{ route('arus-kas.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn btn-primary mb-3">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>

            <div class="row">
                <!-- Penerimaan Kas -->
                <div class="col-md-6">
                    <h5>Penerimaan Kas</h5>
                    <ul class="list-group mb-3">
                        @forelse ($penerimaan as $trx)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <div>{{ $trx->account->name }} - <small class="text-muted">{{ $trx->reference }}</small> </div>
                            </div>
                            <span>Rp {{ number_format($trx->debit, 0, ',', '.') }}</span>
                        </li>

                        @empty
                        <li class="list-group-item text-center text-muted">Tidak ada penerimaan kas</li>
                        @endforelse
                        <li class="list-group-item d-flex justify-content-between fw-bold">
                            Total Masuk
                            <span>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Pengeluaran Kas -->
                <div class="col-md-6">
                    <h5>Pengeluaran Kas</h5>
                    <ul class="list-group mb-3">
                        @forelse ($pengeluaran as $trx)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>{{ $trx->account->name }} - <small class="text-muted">{{ $trx->reference }}</small> </div>
                            <span>Rp {{ number_format($trx->credit, 0, ',', '.') }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Tidak ada pengeluaran kas</li>
                        @endforelse
                        <li class="list-group-item d-flex justify-content-between fw-bold">
                            Total Keluar
                            <span>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Saldo Akhir -->
            <div class="alert alert-info text-center mt-4">
                <h5 class="mb-0">Saldo Kas Akhir: Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h5>
            </div>

        </div>
    </div>
</div>
@endsection