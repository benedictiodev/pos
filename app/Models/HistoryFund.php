<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryFund extends Model
{
    use HasFactory;

    protected $table = 'history_fund';
    protected $fillable = [
        'company_id',
        'from_type',
        'to_type',
        'amount',
        'datetime',
    ];
}
