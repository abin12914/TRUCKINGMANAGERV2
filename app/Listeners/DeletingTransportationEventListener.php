<?php

namespace App\Listeners;

use App\Events\DeletingTransportationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingTransportationEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingTransportationEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingTransportationEvent  $event
     * @return void
     */
    public function handle(DeletingTransportationEvent $event)
    {
        try {
            $transaction = $event->transportation->transaction;
            $event->transportation->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
