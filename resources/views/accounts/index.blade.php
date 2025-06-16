@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<h3 class="mb-4 fw-semibold">Daftar Akun</h3>

<div class="text-end mb-3">
    <a href="{{ route('accounts.create') }}" class="btn btn-success">+ Tambah Akun</a>
</div>

<form method="GET" action="{{ route('accounts.index') }}" class="mb-3">
    <div class="col-md-4 col-lg-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kode Akun" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
    </div>
</form>

<div class="card shadow-sm rounded">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Saldo Normal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($accounts as $account)
                <tr>
                    <td class="px-4 py-2">{{ $account->code }}</td>
                    <td class="px-4 py-2">{{ $account->name }}</td>
                    <td class="px-4 py-2">{{ ucfirst($account->type) }}</td>
                    <td class="px-4 py-2 text-capitalize">{{ $account->normal_balance }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Tidak ada data akun.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection