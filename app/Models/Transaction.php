<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['account_id', 'date', 'reference', 'reference_id', 'debit', 'credit', 'status'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function journalTransactions()
    {
        return $this->hasMany(Transaction::class, 'journal_id', 'journal_id');
    }
}
