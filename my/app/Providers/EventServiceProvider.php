<?php

namespace App\Providers;

use Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\RegisterMailListener',
        ],
        'App\Events\CompanyRegisterEvent' => [
            'App\Listeners\CompanyRegisterEventListener',
        ],
        'App\Events\CompanyStatusChangeEvent' => [
            'App\Listeners\CompanyStatusChangeEventListener',
        ]
    ];

    /**
     * 登録する購読クラス
     *
     * @var array
     */
    protected $subscribe = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
