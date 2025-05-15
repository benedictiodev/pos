<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementCashIn extends Model
{
    use HasFactory;

    protected $table = 'management_cash_in';
    protected $guarded = ['id'];
}
