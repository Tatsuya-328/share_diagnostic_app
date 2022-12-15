<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Mail;
use App\Mail\ProvisionalMailer;
use Log;

class RegisterMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (empty($event->user)) {
            Log::info('error. user not found');
        }
        if ($event->user->status == User::STATUS_TYPE_PROVISIONAL) {
            $event->user->provisionalRegisterNotification();
        } elseif ($event->user->status == User::STATUS_TYPE_MEMBER) {
            $event->user->verifyRegisterNotification();
        }
    }

    public function failed(MailEvent $event, $exception)
    {
        Log::info('failed. register mail was not send.');
    }
}
