<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        // Récupérer toutes les factures
        $invoices = Invoice::all();

        // Retourner la vue avec les factures
        return view('invoices.index', compact('invoices'));
    }
}
