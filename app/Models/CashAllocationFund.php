<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashAllocationFund extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'management_cash_allocation_funds_tables';
    protected $guarded = ['id'];

    public function from_type() {
        return $this->belongsTo(ManagementFund::class, 'from_type_id');
    }

    public function to_type() {
        return $this->belongsTo(ManagementFund::class, 'to_type_id');
    }
}
