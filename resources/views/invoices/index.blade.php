<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Factures</title>
</head>
<body>
    <div>
        <h1>Liste des Factures</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client ID</th>
                    <th>Montant Total</th>
                    <th>Date d'Envoi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->client_id }}</td>
                        <td>{{ $invoice->total_amount }} €</td>
                        <td>{{ $invoice->send_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $invoices->links() }}
        </div>
    </div>
</body>
</html>
