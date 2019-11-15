<?php

namespace App\Listeners;

use App\Events\DeletingExpenseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingExpenseEventListener
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
     * @param  DeletingExpenseEvent  $event
     * @return void
     */
    public function handle(DeletingExpenseEvent $event)
    {
        $transaction = $event->expense->transaction;
        $fuelRefill  = $event->expense->fuelRefill;
        if(!empty($fuelRefill)) {
            $event->expense->isForceDeleting() ? $fuelRefill->forceDelete() : $fuelRefill->delete();
        }
        $event->expense->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
    }
}
