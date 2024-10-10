<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
