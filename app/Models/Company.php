<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\CreatedCompanyEvent;

class Company extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CreatedCompanyEvent::class,
    ];
}
