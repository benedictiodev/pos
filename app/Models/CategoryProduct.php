<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $fillable = ["name", 'company_id'];

    function products()
    {
        return $this->hasOne(Product::class);
    }
}
