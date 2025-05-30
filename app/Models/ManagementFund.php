<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementFund extends Model
{
    use HasFactory;

    protected $table = 'management_funds';
    protected $guarded = ['id'];
}
