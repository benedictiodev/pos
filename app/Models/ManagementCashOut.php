<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementCashOut extends Model
{
    use HasFactory;

    protected $table = 'management_cash_out';
    protected $guarded = ['id'];
}
