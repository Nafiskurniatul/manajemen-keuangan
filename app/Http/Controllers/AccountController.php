<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $accountsQuery = Account::query();
        $accountsQuery->when($search, function ($query, $search) {
        $query->where('code', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%');
        });
        
        $accountsQuery->whereNotNull('type');

        $accounts = $accountsQuery->latest()->paginate(10)->appends(['search' => $search]);
        return view('accounts.index', compact('accounts'));
    }


    public function create()
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('accounts.index')->with('error', 'Tidak Memiliki Akses');
        }
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('accounts.index')->with('error', 'Tidak Memiliki Akses');
        }
        $request->validate([
            'code' => 'required|unique:accounts',
            'name' => 'required',
            'type' => 'required|in:Aktiva,Kewajiban,Ekuitas,Pendapatan,Beban',
            'normal_balance' => 'required|in:debit,credit',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(Account $account)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('accounts.index')->with('error', 'Tidak Memiliki Akses');
        }
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('accounts.index')->with('error', 'Tidak Memiliki Akses');
        }
        $request->validate([
            'code' => 'required|unique:accounts,code,' . $account->id,
            'name' => 'required',
            'type' => 'required|in:Aktiva,Kewajiban,Ekuitas,Pendapatan,Beban',
            'normal_balance' => 'required|in:debit,credit',
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('accounts.index')->with('error', 'Tidak Memiliki Akses');
        }
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
