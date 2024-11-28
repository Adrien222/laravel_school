<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class SendAWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'mails'; // Définit la queue pour les emails

    /**
     * Handle the event.
     *
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Mail::raw(
            "Bravo {$event->name}, vous faites maintenant partie de notre programme de fidélité !",
            function (Message $message) use ($event) {
                $message->to($event->email, $event->name)
                    ->subject('Bienvenue chez nous !');
            }
        );
    }
}
