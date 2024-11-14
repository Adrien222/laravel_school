<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use App\Enums\Roles;

class InvoicePolicy
{
    /**
     * Determine if the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [Roles::Admin, Roles::SuperAdmin]);
    }

    /**
     * Determine if the user can view a specific invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, [Roles::Admin, Roles::SuperAdmin]);
    }

    /**
     * Determine if the user can create an invoice.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [Roles::Admin, Roles::SuperAdmin]);
    }

    /**
     * Determine if the user can delete a specific invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->role === Roles::SuperAdmin;
    }
}
