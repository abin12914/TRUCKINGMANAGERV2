<?php

namespace App\Listeners;

use App\Events\DeletingSaleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exceptions\TMException;

class DeletingSaleEventListener
{
    protected $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listnerCode = config('settings.listener_code.DeletingSaleEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  DeletingSaleEvent  $event
     * @return void
     */
    public function handle(DeletingSaleEvent $event)
    {
        try {
            $transaction = $event->sale->transaction;
            $event->sale->isForceDeleting() ? $transaction->forceDelete() : $transaction->delete();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
