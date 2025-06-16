@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
<h3 class="mb-4 fw-semibold">Edit Akun</h3>

<div class="card shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('accounts.update', $account) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kode Akun</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $account->code) }}">
                @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Akun</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}">
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tipe Akun</label>
                <select name="type" class="form-select">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach (['Aktiva' => 'Aktiva', 'Kewajiban' => 'Kewajiban', 'Ekuitas' => 'Ekuitas', 'Pendapatan' => 'Pendapatan', 'Beban' => 'Beban'] as $key => $val)
                    <option value="{{ $key }}" {{ old('type', $account->type) == $key ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
                @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Saldo Normal</label>
                <select name="normal_balance" class="form-select">
                    <option value="">-- Pilih Saldo Normal --</option>
                    <option value="debit" {{ old('normal_balance', $account->normal_balance) == 'debit' ? 'selected' : '' }}>Debit</option>
                    <option value="credit" {{ old('normal_balance', $account->normal_balance) == 'credit' ? 'selected' : '' }}>Kredit</option>
                </select>
                @error('normal_balance') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-start gap-2">
                <button type="submit" class="btn btn-success">Perbarui</button>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection