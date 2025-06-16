<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $transactions = Transaction::with('account')
            ->whereHas('account', function ($query) {
                $query->where('code', 'like', '00%');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(102)
            ->appends(['search' => $search]); // penting agar pagination tetap menyimpan kata kunci

        return view('transactions.index', compact('transactions', 'search'));
    }

    public function post($reference_id)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('transactions.index')->with('error', 'Tidak Memiliki Akses');
        }
        $transactions = Transaction::where('reference_id', $reference_id)->get();

        if ($transactions->isEmpty()) {
            return redirect()->route('transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        foreach ($transactions as $trx) {
            if ($trx->status === 'posted') {
                return redirect()->route('transactions.index')->with('info', 'Transaksi sudah diposting.');
            }

            $trx->update(['status' => 'posted']);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diposting.');
    }

    public function create(Request $request)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('transactions.index')->with('error', 'Tidak Memiliki Akses');
        }

        $type = $request->type ?? 'income';

        // Ambil akun bank/kas (kode mulai dari 00)
        $bank = Account::where('code', 'like', '00%')->get();
        $account_title = $type === 'income' ? 'Akun Kredit' : 'Akun Debit';

        // Tentukan akun berdasarkan normal_balance dan pastikan type tidak null
        if ($type === 'income') {
            $accounts_credit = Account::where('normal_balance', 'credit')
                ->whereNotNull('type')
                ->get();
        } else {
            $accounts_credit = Account::where('normal_balance', 'debit')
                ->whereNotNull('type')
                ->where('code', 'not like', '00%')
                ->get();
        }

        return view('transactions.create', compact('bank', 'accounts_credit', 'type', 'account_title'));
    }


    public function store(Request $request)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('transactions.index')->with('error', 'Tidak Memiliki Akses');
        }
        $request->validate([
            'account_debit_id' => 'required|exists:accounts,id|different:account_credit_id',
            'account_credit_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string',
            'type' => 'required|in:income,expense',
        ]);

        $referenceId = Str::uuid();

        if ($request->type === 'income') {
            // Debit duluan
            Transaction::create([
                'reference_id' => $referenceId,
                'account_id' => $request->account_debit_id,
                'date' => $request->date,
                'reference' => $request->reference,
                'debit' => $request->amount,
                'credit' => 0,
            ]);

            Transaction::create([
                'reference_id' => $referenceId,
                'account_id' => $request->account_credit_id,
                'date' => $request->date,
                'reference' => $request->reference,
                'debit' => 0,
                'credit' => $request->amount,
            ]);
        } else {
            // Dibalik
            Transaction::create([
                'reference_id' => $referenceId,
                'account_id' => $request->account_credit_id,
                'date' => $request->date,
                'reference' => $request->reference,
                'debit' => $request->amount,
                'credit' => 0,
            ]);

            Transaction::create([
                'reference_id' => $referenceId,
                'account_id' => $request->account_debit_id,
                'date' => $request->date,
                'reference' => $request->reference,
                'debit' => 0,
                'credit' => $request->amount,
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }


    public function destroy(Transaction $transaction)
    {
        $role = session('role');

        if ($role === 'manajer') {
            return redirect()->route('transactions.index')->with('error', 'Tidak Memiliki Akses');
        }
        $transaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
