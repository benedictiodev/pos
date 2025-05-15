<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementCashMonthly extends Model
{
    use HasFactory;

    protected $table = 'management_cash_monthly';
    protected $guarded = ['id'];
}
