<?php

namespace App\Listeners;

use App\Events\DeletingVoucherEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exceptions\TMException;

class DeletingVoucherEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingVoucherEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingVoucherEvent  $event
     * @return void
     */
    public function handle(DeletingVoucherEvent $event)
    {
        try {
            $transaction = $event->voucher->transaction;
            $event->voucher->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
