<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $kasAccountIds = Account::where('type', 'Aktiva')
            ->whereIn('name', ['Kas', 'Bank', 'Kas Besar', 'Kas Kecil'])
            ->pluck('id');

        $monthlyData = [];
        $totalMasuk = 0;
        $totalKeluar = 0;

        // Loop 12 bulan ke belakang
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth()->toDateString();
            $end   = $date->copy()->endOfMonth()->toDateString();

            $transactions = Transaction::with('account')
                ->whereBetween('date', [$start, $end])
                ->whereIn('account_id', $kasAccountIds)
                ->get();

            $masuk = 0;
            $keluar = 0;

            foreach ($transactions as $trx) {
                $masuk += $trx->debit;
                $keluar += $trx->credit;
            }

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'masuk' => $masuk,
                'keluar' => $keluar,
                'saldo' => $masuk - $keluar,
            ];

            // Hanya total bulan ini
            if ($i === 0) {
                $totalMasuk = $masuk;
                $totalKeluar = $keluar;
            }
        }

        $saldoAkhir = $totalMasuk - $totalKeluar;

        return view('dashboard.index', compact('totalMasuk', 'totalKeluar', 'saldoAkhir', 'monthlyData'));
    }
}
