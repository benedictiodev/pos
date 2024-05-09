<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["name", 'company_id'];

    function products()
    {
        return $this->hasOne(Product::class);
    }
}
