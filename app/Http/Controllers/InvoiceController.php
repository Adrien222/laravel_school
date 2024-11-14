<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tool;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Controller as BaseController;

class InvoiceController extends BaseController
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        $this->authorizeResource(Invoice::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('tools')->get();

        return Blade::render('
            <h1>Factures</h1>
            <ul>
                @foreach ($invoices as $invoice)
                    <li>Facture #{{ $invoice->id }} : {{ $invoice->total_amount }}€</li>
                @endforeach
            </ul>
            ', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Blade::render('
            <h1>Créer une facture</h1>
            <form action="{{ route(\'invoices.store\') }}" method="POST">
                @csrf
                <input type="number" name="number_of_items">
                <input type="submit" value="Créer">
            </form>
        ');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'number_of_items' => 'required|integer|min:1|max:10'
        ]);

        $items = [];

        for ($i = 0; $i < $data['number_of_items']; $i++) {
            $items[] = Tool::query()->inRandomOrder()->first();
        }

        $amount_before_tax = array_reduce($items, function ($carry, $item) {
            return $carry + $item->price->toArray()['amount'];
        }, 0);

        $tax = $amount_before_tax * 0.2;

        $invoice = Invoice::query()->create([
            'client_id' => auth()->id(),
            'purchase_order_id' => rand(1, 1000000),
            'total_amount' => $amount_before_tax + $tax,
            'amount_before_tax' => $amount_before_tax,
            'tax' => $tax,
            'send_at' => now(),
        ]);

        collect($items)->groupBy('id')->each(function ($item) use ($invoice) {
            $invoice->tools()->attach($item->first()->id, ['quantity' => $item->count()]);
        });

        return to_route('invoices.show', $invoice->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('tools');
        $invoice->tools()->withPivot('quantity');

        return Blade::render('
            <h1>Facture #{{ $invoice->id }}</h1>

            @foreach ($invoice->tools as $tool)
                <table>
                  <thead>
                    <tr>
                      <th>Nom du produit</th>
                      <th>Quantité</th>
                      <th>Prix unitaire</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $tool->name }}</td>
                      <td>{{ $tool->pivot->quantity }}</td>
                      <td>{{ $tool->price->toArray()[\'amount\'] }}€</td>
                    </tr>
                  </tbody>
                </table>
            @endforeach

            @can(\'delete\', $invoice)

            <form action="{{ route(\'invoices.destroy\', $invoice->id) }}" method="POST">
                @csrf
                @method(\'DELETE\')
                <input type="submit" value="Supprimer">
            </form>
            @endcan
        ', compact('invoice'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return to_route('invoices.index');
    }
}
