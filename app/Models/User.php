<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Enums\Roles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'authentication_token',
        'authentication_token_generated_at',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'authentication_token',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'authentication_token_generated_at' => 'datetime',
            'role' => 'string',
        ];
    }

    /**
     * Relation with invoices
     * Each user can have multiple invoices.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    /**
     * Send an authentication email with a magic link to the user.
     *
     * @param string|null $redirect_to
     */
    public function sendAuthenticationMail(?string $redirect_to = null): void
    {
        $authenticationService = new AuthenticationService($this);

        $url = route('authentication.callback', [
            'token' => $authenticationService->createToken(),
            'email' => $this->email,
            'redirect_to' => $redirect_to ?? url("/home"),
        ]);

        Mail::raw("Pour vous identifier au site, veuillez cliquer <a href='$url'>ici</a>", function (Message $mail) {
            $mail->to($this->email)
                ->from('no-reply@u-picardie.fr')
                ->subject('Connectez-vous à votre site préféré');
        });
    }
}
