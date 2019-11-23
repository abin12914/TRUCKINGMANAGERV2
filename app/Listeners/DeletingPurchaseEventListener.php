<?php

namespace App\Listeners;

use App\Events\DeletingPurchaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingPurchaseEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingPurchaseEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingPurchaseEvent  $event
     * @return void
     */
    public function handle(DeletingPurchaseEvent $event)
    {
        try {
            $transaction = $event->purchase->transaction;
            $event->purchase->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
