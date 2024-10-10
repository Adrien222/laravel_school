<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    // Method for displaying all invoices in the index view
    public function index()
    {
        // Récupérer toutes les factures
        $invoices = Invoice::all();

        // Retourner la vue avec les factures
        return view('invoices.index', compact('invoices'));
    }


    public function search(Request $request)
    {
        $query = Invoice::query();

        // Filtrer par email si fourni
        if ($request->filled('email')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('email', $request->email);
            });
        }

        // Filtrer par prix minimum si fourni
        if ($request->filled('price_higher_than')) {
            $query->where('total_amount', '>=', $request->price_higher_than);
        }

        // Filtrer par prix maximum si fourni
        if ($request->filled('price_lower_than')) {
            $query->where('total_amount', '<=', $request->price_lower_than);
        }

        // Charger la relation 'client' et compter le nombre d'outils associés à chaque facture
        $invoices = $query->with(['client'])
                            ->withCount('tools')
                            ->get();

        // Retourner la vue avec les résultats de recherche
        return view('search', compact('invoices'));
    }
}
