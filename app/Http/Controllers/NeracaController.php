<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
         $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        // Ambil data akun dan transaksi hanya jika tanggal ada
        $accounts = Account::with(['transactions' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }])->get();

        $grouped = [
            'Aktiva'    => [],
            'Kewajiban' => [],
            'Ekuitas'   => [],
        ];

        foreach ($accounts as $account) {
            if (!in_array($account->type, ['Aktiva', 'Kewajiban', 'Ekuitas'])) {
                continue; // Skip Pendapatan dan Beban
            }

            $debit  = $account->transactions->sum('debit');
            $credit = $account->transactions->sum('credit');

            // Debug: tampilkan informasi akun dan transaksi
            logger()->info("Account: {$account->name} ({$account->type})");
            logger()->info("  Debit: $debit");
            logger()->info("  Credit: $credit");

            $balance = match ($account->type) {
                'Aktiva'    => $debit - $credit,
                'Kewajiban' => $credit - $debit,
                'Ekuitas'   => $credit - $debit,
            };

            $grouped[$account->type][] = [
                'name'    => $account->name,
                'balance' => $balance,
            ];
        }

        // Debug akhir: tampilkan semua hasil grouped
        logger()->info('Grouped Result:', $grouped);

        return view('neraca.index', [
            'grouped'   => [
                'asset'     => $grouped['Aktiva'],
                'liability' => $grouped['Kewajiban'],
                'equity'    => $grouped['Ekuitas'],
            ],
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $accounts = Account::with(['transactions' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }])->get();

        $grouped = [
            'Aktiva'    => [],
            'Kewajiban' => [],
            'Ekuitas'   => [],
        ];

        foreach ($accounts as $account) {
            if (!in_array($account->type, ['Aktiva', 'Kewajiban', 'Ekuitas'])) {
                continue;
            }

            $debit  = $account->transactions->sum('debit');
            $credit = $account->transactions->sum('credit');

            $balance = match ($account->type) {
                'Aktiva'    => $debit - $credit,
                'Kewajiban' => $credit - $debit,
                'Ekuitas'   => $credit - $debit,
            };

            $grouped[$account->type][] = [
                'name'    => $account->name,
                'balance' => $balance,
            ];
        }

        return view('neraca.print', [
            'grouped'   => [
                'asset'     => $grouped['Aktiva'],
                'liability' => $grouped['Kewajiban'],
                'equity'    => $grouped['Ekuitas'],
            ],
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
