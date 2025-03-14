<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    function category_product()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
