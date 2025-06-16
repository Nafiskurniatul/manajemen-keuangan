@extends('layouts.app')
@section('title', 'Laporan Buku Besar')
@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3 fw-semibold">Laporan Buku Besar</h5>

        <form method="GET" action="{{ route('buku-besar.index') }}" class="row mb-4">
            <div class="col-md-4">
                <label>Akun</label>
                <select name="account_id" class="form-control">
                    <option value="">-- Semua Akun --</option>
                    @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>

        @if($groupedTransactions->count())
        <div class="mb-3 text-start">
            <a href="{{ route('buku-besar.cetak', request()->query()) }}" class="btn btn-primary mb-3">
                <i class="bi bi-printer"></i> Cetak
            </a>
        </div>
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @foreach($groupedTransactions as $accountName => $transactions)
        <h6 class="fw-bold mt-4">{{ $accountName }}</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                    @endphp

                    @foreach($transactions as $t)
                    @php
                    $totalDebit += $t->debit;
                    $totalCredit += $t->credit;
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}</td>
                        <td>{{ $t->reference }}</td>
                        <td class="text-end">Rp {{ number_format($t->debit, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($t->credit, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-end">Total</td>
                        <td class="text-end">Rp {{ number_format($totalDebit, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($totalCredit, 2, ',', '.') }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection