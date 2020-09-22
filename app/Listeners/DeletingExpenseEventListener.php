<?php

namespace App\Listeners;

use App\Events\DeletingExpenseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exceptions\TMException;

class DeletingExpenseEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingExpenseEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingExpenseEvent  $event
     * @return void
     */
    public function handle(DeletingExpenseEvent $event)
    {
        try {
            $transaction = $event->expense->transaction;
            $fuelRefill  = $event->expense->fuelRefill;
            if(!empty($fuelRefill)) {
                $event->expense->isForceDeleting() ? $fuelRefill->forceDelete() : $fuelRefill->delete();
            }
            $event->expense->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
