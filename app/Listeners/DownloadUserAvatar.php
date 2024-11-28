<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DownloadUserAvatar implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'avatars'; // Définit la queue pour les avatars

    /**
     * Handle the event.
     *
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $url = "https://ui-avatars.com/api/?name=" . urlencode($event->name);

        $avatar = file_get_contents($url);

        if ($avatar !== false) {
            Storage::put("avatars/{$event->name}.png", $avatar);
        }
    }
}
