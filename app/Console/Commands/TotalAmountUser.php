<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Invoice;

class TotalAmountUser extends Command
{
    // Nom de la commande
    protected $signature = 'user:total-amount {--id= : ID de l\'utilisateur} {--email= : Email de l\'utilisateur}';

    // Description de la commande
    protected $description = 'Calcule la somme totale de toutes les factures d\'un utilisateur';

    // Exécution de la commande
    public function handle()
    {
        $userId = $this->option('id');
        $userEmail = $this->option('email');

        // Vérifier qu'une option est présente
        if (!($userId ^ $userEmail)) {
            $this->error("Vous devez spécifier soit l'ID soit l'email de l'utilisateur.");
            return 1;
        }

        // Récupérer l'utilisateur selon l'option
        $user = null;
        if ($userId) {
            $user = User::find($userId);
        } elseif ($userEmail) {
            $user = User::where('email', $userEmail)->first();
        }

        // Vérifier que l'utilisateur existe
        if (!$user) {
            $this->error("Utilisateur non trouvé.");
            return 1;
        }

        // Calculer le montant total des factures de l'utilisateur
        $totalAmount = $user->invoices()->sum('total_amount'); // Assurez-vous que la relation "invoices" est définie sur le modèle User

        $this->info("La somme totale des factures de l'utilisateur {$user->name} est de : {$totalAmount} €");
    }
}
