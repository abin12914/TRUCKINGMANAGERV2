<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\CreatedCompanyEvent' => [
            'App\Listeners\CreatedCompanyEventListener',
        ],
        'App\Events\DeletingExpenseEvent' => [
            'App\Listeners\DeletingExpenseEventListener',
        ],
        'App\Events\DeletingTransportationEvent' => [
            'App\Listeners\DeletingTransportationEventListener',
        ],
        'App\Events\DeletingPurchaseEvent' => [
            'App\Listeners\DeletingPurchaseEventListener',
        ],
        'App\Events\DeletingSaleEvent' => [
            'App\Listeners\DeletingSaleEventListener',
        ],
        'App\Events\DeletingSaleEvent' => [
            'App\Listeners\DeletingSaleEventListener',
        ],
        'App\Events\DeletingVoucherEvent' => [
            'App\Listeners\DeletingVoucherEventListener',
        ],
        'App\Events\DeletingEmployeeWageEvent' => [
            'App\Listeners\DeletingEmployeeWageEventListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
