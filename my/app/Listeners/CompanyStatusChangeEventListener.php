<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PartnerImage;

class CompanyStatusChangeEventListener
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
        $companyChangeRequest = $event->companyChangeRequest;
        foreach ($companyChangeRequest->company->users as $user) {
            $user->changeCompanyStatusNotification($companyChangeRequest);
        }
        PartnerImage::deleteList($event->deleteImageIds);
    }
}
