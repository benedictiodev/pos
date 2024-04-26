<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone_number',
        'type_subscription',
        'subscription_fee',
        'expired_date',
        'grace_days_ended_at',
    ];
}
