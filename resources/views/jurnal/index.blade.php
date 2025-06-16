@extends('layouts.app')

@section('title', 'Daftar Jurnal')
@section('content')
<h3 class="mb-4 fw-semibold">Jurnal Umum</h3>

<div class="mb-3">
    <form method="GET" action="{{ route('jurnal.index') }}" class="row gy-2 gx-3 align-items-center">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari referensi / deskripsi" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </div>
    </form>
</div>


<div class="card shadow-sm rounded">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groupedTransactions as $referenceId => $transactions)
                @php
                    $first = $transactions->first();
                    $totalDebit = $transactions->sum('debit');
                    $totalCredit = $transactions->sum('credit');
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($first->date)->format('d-m-Y') }}</td>
                    <td>{{ $first->reference }}</td>
                    <td>Rp {{ number_format($totalDebit, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($totalCredit, 2, ',', '.') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                Tindakan
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('jurnal.show', $referenceId) }}">Lihat Ayat Jurnal</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Data jurnal tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
