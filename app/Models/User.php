<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;


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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'authentication_token', // On masque le token
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
        ];
    }

    public function sendAuthenticationMail(?string $redirect_to = null): void
    {
        $authenticationSerive = new AuthenticationService($this);

        $url = route('authentication.callback', [
            'token' => $authenticationSerive->createToken(),
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
