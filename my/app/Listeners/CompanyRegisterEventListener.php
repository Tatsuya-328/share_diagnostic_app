<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\UsersCompany;
use Auth;

class CompanyRegisterEventListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        UsersCompany::insert([
            'user_id' => Auth::id(),
            'company_id' => $event->company->id,
            'is_owner' => true
        ]);
    }
}
