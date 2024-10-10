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

    protected $casts = [
        'send_at' => 'datetime',
        'acquitted_at' => 'datetime',
    ];

    // Relation ManyToOne : une facture appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    // Relation ManyToMany : une facture peut contenir plusieurs outils
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'invoice_tool');
    }
}
