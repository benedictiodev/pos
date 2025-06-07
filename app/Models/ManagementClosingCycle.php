<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementClosingCycle extends Model
{
    use HasFactory;

    protected $table = 'management_cash_closing_cycle';
    protected $guarded = ['id'];
}
