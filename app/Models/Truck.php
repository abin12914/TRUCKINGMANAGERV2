<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
	//landlord
    use BelongsToTenants;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'insurance_upto', 'tax_upto', 'fitness_upto', 'permit_upto', 'pollution_upto'];

    /**
     * Scope a query to only include active employees.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the truck type details of the vehicle
     */
    public function truckType()
    {
        return $this->belongsTo('App\Models\TruckType');
    }
}
