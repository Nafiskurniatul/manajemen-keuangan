<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;

class ArusKasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        // Ambil ID akun kas dari tabel accounts
        $kasAccountIds = Account::where('type', 'Aktiva')
            ->whereIn('name', ['Kas', 'Bank', 'Kas Besar', 'Kas Kecil'])
            ->pluck('id');

        // Ambil transaksi yang melibatkan akun kas
        $transactions = Transaction::with('account')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('account_id', $kasAccountIds)
            ->orderBy('date')
            ->get();

        $penerimaan = [];  // debit ke akun kas (kas masuk)
        $pengeluaran = []; // kredit dari akun kas (kas keluar)
        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($transactions as $trx) {
            $lawans = $trx->journalTransactions->filter(function ($item) use ($trx) {
                return $item->id !== $trx->id;
            });

            $lawanName = $lawans->pluck('account.name')->implode(', ');

            // Tambahkan references
            $keterangan = $lawanName;
            if ($trx->references) {
                $keterangan .= ' - ' . $trx->references;
            }

            $trx->keterangan = $keterangan;

            if ($trx->debit > 0) {
                $penerimaan[] = $trx;
                $totalMasuk += $trx->debit;
            }

            if ($trx->credit > 0) {
                $pengeluaran[] = $trx;
                $totalKeluar += $trx->credit;
            }
        }



        $saldoAkhir = $totalMasuk - $totalKeluar;

        return view('arus_kas.index', compact(
            'startDate',
            'endDate',
            'penerimaan',
            'pengeluaran',
            'totalMasuk',
            'totalKeluar',
            'saldoAkhir'
        ));
    }
    public function print(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        $kasAccountIds = Account::where('type', 'Aktiva')
            ->whereIn('name', ['Kas', 'Bank', 'Kas Besar', 'Kas Kecil'])
            ->pluck('id');
        $transactions = Transaction::with('account')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('account_id', $kasAccountIds)
            ->orderBy('date')
            ->get();

        $penerimaan = [];
        $pengeluaran = [];
        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($transactions as $trx) {
            $lawans = $trx->journalTransactions->filter(fn($item) => $item->id !== $trx->id);
            $lawanName = $lawans->pluck('account.name')->implode(', ');
            $keterangan = $lawanName;

            if ($trx->references) {
                $keterangan .= ' - ' . $trx->references;
            }

            $trx->keterangan = $keterangan;

            if ($trx->debit > 0) {
                $penerimaan[] = $trx;
                $totalMasuk += $trx->debit;
            }

            if ($trx->credit > 0) {
                $pengeluaran[] = $trx;
                $totalKeluar += $trx->credit;
            }
        }

        $saldoAkhir = $totalMasuk - $totalKeluar;

        return view('arus_kas.print', compact(
            'startDate',
            'endDate',
            'penerimaan',
            'pengeluaran',
            'totalMasuk',
            'totalKeluar',
            'saldoAkhir'
        ));
    }
}
