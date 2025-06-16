<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date') ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->input('end_date') ?? now()->endOfMonth()->toDateString();

        $accounts = Account::orderBy('type')->orderBy('name')->get();
        $groupedTransactions = collect();

        if ($accountId) {
            $account = Account::find($accountId);
            $transactions = Transaction::where('account_id', $accountId)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();

            if ($transactions->isNotEmpty()) {
                $groupedTransactions->put($account->name, $transactions);
            }
        } else {
            foreach ($accounts as $account) {
                $transactions = Transaction::where('account_id', $account->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get();

                if ($transactions->isNotEmpty()) {
                    $groupedTransactions->put($account->name, $transactions);
                }
            }
        }

        return view('buku-besar.index', compact('accounts', 'groupedTransactions', 'startDate', 'endDate'));
    }

    public function cetak(Request $request)
    {
        // Default tanggal ke awal dan akhir bulan jika tidak ada input
        $startDate = $request->input('start_date') ?? now()->startOfMonth()->toDateString();
        $endDate = $request->input('end_date') ?? now()->endOfMonth()->toDateString();
        $accountId = $request->input('account_id');

        $groupedTransactions = collect();

        if ($accountId) {
            $account = Account::find($accountId);
            $transactions = Transaction::where('account_id', $account->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();

            if ($transactions->isNotEmpty()) {
                $groupedTransactions->put($account->name, $transactions);
            }
        } else {
            $accounts = Account::orderBy('type')->orderBy('name')->get();
            foreach ($accounts as $account) {
                $transactions = Transaction::where('account_id', $account->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get();

                if ($transactions->isNotEmpty()) {
                    $groupedTransactions->put($account->name, $transactions);
                }
            }
        }

        $pdf = Pdf::loadView('buku-besar.cetak', compact('groupedTransactions', 'startDate', 'endDate'));
        return $pdf->download('buku-besar.pdf');
    }
}
