<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingCycle extends Model
{
    use HasFactory;
    protected $table = 'closing_cycle';
    protected $guarded = ['id'];
}
