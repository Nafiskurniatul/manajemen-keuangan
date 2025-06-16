<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class LabaRugiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        // Ambil transaksi terkait akun bertipe Pendapatan dan Beban
        $transactions = Transaction::with('account')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereHas('account', function ($query) {
                $query->whereIn('type', ['Pendapatan', 'Beban']);
            })
            ->get();
        
        //mengelompokkan transaksi berdasarkan tipe Pendapatan dan Beban.
        $data = [
            'Pendapatan' => [],
            'Beban' => [],
        ];

        // Mnyimpan total nilai Pendapatan dan Beban
        $totals = [
            'Pendapatan' => 0,
            'Beban' => 0,
        ];

        // Looping melalui setiap transksi yang diambil
        foreach ($transactions as $trx) {
            $type = $trx->account->type; 

            if ($type === 'Pendapatan') { // mengelompokkan transaksi sesuai dengan kategorinya
                $data['Pendapatan'][] = $trx;
                $totals['Pendapatan'] += $trx->credit; // nilai kredit ditambahkan ke total pendapatan
            } elseif ($type === 'Beban') {
                $data['Beban'][] = $trx;
                $totals['Beban'] += $trx->debit; // nilai debit transaksi akan ditambahkan ke total beban
            }
        }


        // Menghitung laba bersih dengan mengurangi total beban dari total pendapatan.
        $laba_bersih = $totals['Pendapatan'] - $totals['Beban'];

        return view('laba_rugi.index', compact(
            'data',
            'totals',
            'laba_bersih',
            'startDate',
            'endDate'
        ));
    }
    public function print(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        // mengambil transaksi terkait dengan akun pendapatan dan beban dlm rentang tanggal
        $transactions = Transaction::with('account')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereHas('account', function ($query) {
                $query->whereIn('type', ['Pendapatan', 'Beban']);
            })
            ->get();

        $data = ['Pendapatan' => [], 'Beban' => []];
        $totals = ['Pendapatan' => 0, 'Beban' => 0];

        foreach ($transactions as $trx) {
            $type = $trx->account->type;

            if ($type === 'Pendapatan') {
                $data['Pendapatan'][] = $trx;
                $totals['Pendapatan'] += $trx->credit;
            } elseif ($type === 'Beban') {
                $data['Beban'][] = $trx;
                $totals['Beban'] += $trx->debit;
            }
        }

        // Menghitung Laba Bersih
        $laba_bersih = $totals['Pendapatan'] - $totals['Beban'];

        return view('laba_rugi.print', compact( // mengembalikan view'laba_rugi.print' dengan data yang sama 
            'data',
            'totals',
            'laba_bersih',
            'startDate',
            'endDate'
        ));
    }
}
