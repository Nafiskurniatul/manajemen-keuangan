<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();

        $accounts = Account::with([
            'transactions' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ])->get();

        $grouped = [
            'Aktiva' => [],
            'Kewajiban' => [],
            'Ekuitas' => [],
        ];

        $netIncome = 0; // Laba atau Rugi

        foreach ($accounts as $account) {
            $debit = $account->transactions->sum('debit');
            $credit = $account->transactions->sum('credit');

            if ($account->type === 'Pendapatan') {
                $netIncome += ($credit - $debit); // Pendapatan = kredit - debit
                continue;
            }

            if ($account->type === 'Beban') {
                $netIncome -= ($debit - $credit); // Beban = debit - kredit
                continue;
            }

            if (!in_array($account->type, ['Aktiva', 'Kewajiban', 'Ekuitas'])) {
                continue;
            }

            $balance = match ($account->type) {
                'Aktiva' => $debit - $credit,
                'Kewajiban' => $credit - $debit,
                'Ekuitas' => $credit - $debit,
            };

            $grouped[$account->type][] = [
                'name' => $account->name,
                'balance' => $balance,
            ];
        }

        // Tambahkan Laba Ditahan (Laba/Rugi Tahun Berjalan) ke Ekuitas
        $grouped['Ekuitas'][] = [
            'name' => 'Laba Tahun Berjalan',
            'balance' => $netIncome,
        ];

        return view('neraca.index', [
            'grouped' => [
                'asset' => $grouped['Aktiva'],
                'liability' => $grouped['Kewajiban'],
                'equity' => $grouped['Ekuitas'],
            ],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $accounts = Account::with([
            'transactions' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ])->get();

        $grouped = [
            'Aktiva' => [],
            'Kewajiban' => [],
            'Ekuitas' => [],
        ];

        $netIncome = 0;

        foreach ($accounts as $account) {
            $debit = $account->transactions->sum('debit');
            $credit = $account->transactions->sum('credit');

            if ($account->type === 'Pendapatan') {
                $netIncome += ($credit - $debit);
                continue;
            }

            if ($account->type === 'Beban') {
                $netIncome -= ($debit - $credit);
                continue;
            }

            if (!in_array($account->type, ['Aktiva', 'Kewajiban', 'Ekuitas'])) {
                continue;
            }

            $balance = match ($account->type) {
                'Aktiva' => $debit - $credit,
                'Kewajiban' => $credit - $debit,
                'Ekuitas' => $credit - $debit,
            };

            $grouped[$account->type][] = [
                'name' => $account->name,
                'balance' => $balance,
            ];
        }

        $grouped['Ekuitas'][] = [
            'name' => 'Laba Tahun Berjalan',
            'balance' => $netIncome,
        ];

        return view('neraca.print', [
            'grouped' => [
                'asset' => $grouped['Aktiva'],
                'liability' => $grouped['Kewajiban'],
                'equity' => $grouped['Ekuitas'],
            ],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
