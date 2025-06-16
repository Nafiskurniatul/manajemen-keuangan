@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <h4 class="m-4">Laporan Laba Rugi</h4>
              <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between mb-4">
                <form method="GET" class="d-flex flex-wrap gap-2 ">
                    <div class="col-mb-2"> <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
                    </div>
                    <div class="col-mb-2"> <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
                    </div>
                    <div class="col-mb-2"> <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                    </div>
                </form>

                <div class="col-auto">
                    <a
                        href="{{ route('laba-rugi.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        target="_blank"
                        class="btn btn-primary">
                        <i class="bi bi-printer"></i>
                        Cetak
                    </a>
                </div>
            </div>
            
            
            @forelse ($data as $kategori => $transaksis)
            @if (count($transaksis) > 0)
            <div class="card mb-4">
                <div class="card-header bg-light"><strong>{{ $kategori }}</strong></div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        @foreach ($transaksis as $trx) 
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $trx->account->name }} 
                            <span>
                                Rp {{ number_format(
                                                $kategori === 'Pendapatan' ? $trx->credit : $trx->debit,
                                                0, ',', '.'
                                            ) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 text-end fw-bold">
                        Total {{ $kategori }}: Rp {{ number_format($totals[$kategori], 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endif
            @empty
            <p class="text-center">Tidak ada data transaksi.</p>
            @endforelse

            <div class="alert alert-info text-center mt-4">
                <h5 class="mb-0">Laba Bersih: Rp {{ number_format($laba_bersih, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>
@endsection