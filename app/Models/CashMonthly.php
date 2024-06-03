<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMonthly extends Model
{
    use HasFactory;
    protected $table = 'cash_monthly';
    protected $guarded = ['id'];
}
