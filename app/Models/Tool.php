<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\PriceCast;


class Tool extends Model
{
    use HasFactory;
    
    protected $table = 'tools';

    protected $fillable = ['name', 'description', 'price'];

    // Relation ManyToMany : un outil peut apparaître dans plusieurs factures
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_tool');
    }

    // L'utilisation du cast
    protected $casts = [
        'price' => PriceCast::class,
    ];

    // Scope pour filtrer les outils avec un prix supérieur à une valeur donnée
    public function scopeWherePriceGreaterThan($query, $price)
    {
        return $query->where('price->amount', '>', $price);
    }
}
