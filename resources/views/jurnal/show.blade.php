@extends('layouts.app')

@section('title', 'Detail Jurnal')
@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-end">
        <a href="{{ route('jurnal.download', $journal->reference_id) }}"
           class="btn btn-sm btn-outline-primary">
            ⬇️ Unduh
        </a>
    </div>
</div> 

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="text-center mb-4 fw-semibold">Journal Entry</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Journal</strong> : {{ $journal->reference ?? '-' }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Tanggal</strong> : {{ \Carbon\Carbon::parse($journal->date)->format('d-m-Y') }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-white">
                    <tr>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $item)
                    <tr>
                        <td>{{ $item->account->name ?? '-' }}</td>
                        <td>{{ $item->reference ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($item->debit, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->credit, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total</th>
                        <th class="text-end">Rp {{ number_format($transactions->sum('debit'), 2, ',', '.') }}</th>
                        <th class="text-end">Rp {{ number_format($transactions->sum('credit'), 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
