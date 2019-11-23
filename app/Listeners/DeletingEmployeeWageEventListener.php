<?php

namespace App\Listeners;

use App\Events\DeletingEmployeeWageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingEmployeeWageEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingEmployeeWageEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingEmployeeWageEvent  $event
     * @return void
     */
    public function handle(DeletingEmployeeWageEvent $event)
    {
        try {
            $transaction = $event->employeeWage->transaction;
            $event->employeeWage->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }

    }
}
