<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Relation OneToMany : un client a plusieurs factures
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
