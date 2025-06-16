<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $groupedTransactions = Transaction::with('account')
            ->where('status', 'posted')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'like', '%' . $search . '%');
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('reference_id');



        return view('jurnal.index', [
            'groupedTransactions' => $groupedTransactions,
            'search' => $search,
        ]);
    }

    public function show($referenceId)
    {
        $transactions = Transaction::with('account')
            ->where('reference_id', $referenceId)
            ->orderBy('id', 'asc')
            ->get();

        if ($transactions->isEmpty()) {
            abort(404);
        }

        $journal = $transactions->first(); // untuk ambil data utama jurnal seperti reference, date, dll

        return view('jurnal.show', [
            'transactions' => $transactions,
            'journal' => $journal,
        ]);
    }

    public function download($referenceId)
    {
        $transactions = Transaction::with('account')
            ->where('reference_id', $referenceId)
            ->orderBy('id', 'asc')
            ->get();

        if ($transactions->isEmpty()) {
            abort(404);
        }

        $journal = $transactions->first();

        // INI PENTING: loadView + download (BUKAN stream)
        $pdf = Pdf::loadView('jurnal.download', compact('transactions', 'journal'));
        return $pdf->download('jurnal-' . $referenceId . '.pdf');
    }
}
