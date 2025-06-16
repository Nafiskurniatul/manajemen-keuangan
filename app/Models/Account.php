<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['code', 'name', 'type', 'normal_balance'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function balance()
    {
        return $this->transactions()->sum('debit') - $this->transactions()->sum('credit');
    }
}
