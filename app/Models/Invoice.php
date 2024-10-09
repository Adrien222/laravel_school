<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'client_id',
        'purchase_order_id',
        'total_amount',
        'amount_before_tax',
        'tax',
        'send_at',
        'acquitted_at',
    ];
}
