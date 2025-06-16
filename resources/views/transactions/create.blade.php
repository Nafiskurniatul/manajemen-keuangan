@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<h3 class="mb-4 fw-semibold">Tambah Transaksi {{ $type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</h3>

<div class="card shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            {{-- Debit (Bank/Kas) tetap --}}
            <div class="mb-3">
                <label class="form-label">Akun Bank/Kas *</label>
                <select name="account_debit_id" class="form-select">
                    <option value="">-- Pilih Akun Debit --</option>
                    @foreach ($bank as $account)
                    <option value="{{ $account->id }}" {{ old('account_debit_id') == $account->id ? 'selected' : '' }}>
                        - {{ $account->name }}
                    </option>
                    @endforeach
                </select>
                @error('account_debit_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Kredit (otomatis menyesuaikan jenis transaksi) --}}
            <div class="mb-3">
                <label class="form-label">
                    {{ $type === 'income' ? 'Akun Kredit' : 'Akun Debit' }} *
                </label>
                <select name="account_credit_id" class="form-select">
                    <option value="">-- Pilih {{ $type === 'income' ? 'Akun Kredit' : 'Akun Debit' }} --</option>
                    @foreach ($accounts_credit as $account)
                    <option value="{{ $account->id }}" {{ old('account_credit_id') == $account->id ? 'selected' : '' }}>
                        {{ $account->code }} - {{ $account->name }}
                    </option>
                    @endforeach
                </select>
                @error('account_credit_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>



            <div class="mb-3">
                <label class="form-label">Tanggal Transaksi</label>
                <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                @error('date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="amount" step="0.01" class="form-control" value="{{ old('amount') }}">
                @error('amount') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Referensi</label>
                <input type="text" name="reference" class="form-control" placeholder="cth. Transfer, Pinjaman, dll" value="{{ old('reference') }}">
                @error('reference') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection