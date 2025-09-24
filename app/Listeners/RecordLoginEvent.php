<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginEvent;

class RecordLoginEvent
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Elke keer dat iemand succesvol inlogt -> record opslaan
        LoginEvent::create([
            'user_id' => $event->user->id,
        ]);
    }
}
