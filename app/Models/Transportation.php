<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportation extends Model
{
	//landlord
    use BelongsToTenants;
    //soft delete
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['transportation_date', 'deleted_at'];

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
     * Get the transaction details associated with the transportation
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the vehicle details associated with the transportation
     */
    public function truck()
    {
        return $this->belongsTo('App\Models\Truck','truck_id');
    }

    /**
     * Get the source site details associated with the transportation
     */
    public function source()
    {
        return $this->belongsTo('App\Models\Site','source_id');
    }

    /**
     * Get the destination site details associated with the transportation
     */
    public function destination()
    {
        return $this->belongsTo('App\Models\Site','destination_id');
    }

    /**
     * Get the material details associated with the transportation
     */
    public function material()
    {
        return $this->belongsTo('App\Models\Material','material_id');
    }

    /**
     * Get the purchase record associated with the transportation if it is a supply.
     */
    public function purchase()
    {
        return $this->hasOne('App\Models\Purchase', 'transportation_id');
    }

    /**
     * Get the sale record associated with the transportation if it is a supply.
     */
    public function sale()
    {
        return $this->hasOne('App\Models\Sale', 'transportation_id');
    }

    /**
     * Get the employee wage record associated with the transportation.
     */
    public function employeeWages()
    {
        return $this->hasMany('App\Models\EmployeeWage', 'transportation_id');
    }
}
