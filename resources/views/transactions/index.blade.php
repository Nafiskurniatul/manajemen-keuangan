@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<h3 class="mb-4 fw-semibold">Daftar Transaksi</h3>

<div class="d-flex justify-content-end">
<div class="dropdown mb-3">
    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Buat Transaksi Baru
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('transactions.create', ['type' => 'income']) }}">
                Buat Pemasukan
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('transactions.create', ['type' => 'expense']) }}">
                Buat Pengeluaran
            </a>
        </li>
    </ul>
</div>
</div>

<form method="GET" action="{{ route('transactions.index') }}" class="mb-3">
    <div class="col-md-4 col-lg-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari referensi atau status..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
    </div>
</form>

<div class="card shadow-sm rounded">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Akun</th>
                    <th class="px-4 py-3">Referensi</th>
                    <th class="px-4 py-3">Debit</th>
                    <th class="px-4 py-3">Kredit</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                <tr>
                    <td class="px-4 py-2">{{ $trx->date }}</td>
                    <td class="px-4 py-2">{{ $trx->account->name }}</td>
                    <td class="px-4 py-2">{{ $trx->reference }}</td>
                    <td class="px-4 py-2">{{ number_format($trx->debit, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($trx->credit, 2) }}</td>
                    <td class="px-4 py-2">
                        <span class="badge bg-{{ $trx->status === 'posted' ? 'success' : 'secondary' }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Tindakan
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger" type="submit">Hapus</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('transactions.post', $trx->reference_id) }}" method="POST" onsubmit="return confirm('Ubah status ke posted?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="dropdown-item">
                                            Ganti Status
                                        </button>
                                    </form>

                                </li>
                            </ul>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $transactions->links() }}
</div>
@endsection