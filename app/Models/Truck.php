<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;

class Truck extends Model
{
	//landlord
    use BelongsToTenants;
    //soft delete
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'insurance_upto', 'tax_upto', 'fitness_upto', 'permit_upto', 'pollution_upto'];

    /**
     * Scope a query to only include active trucks.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * check whether certificate expired
     *
     */
    public function isCertExpired($certificate)
    {
        $today = Carbon::now();

        return $this->$certificate <= $today;
    }

    /**
     * check whether certificate beyod threshold date
     *
     */
    public function isCertCritical($certificate)
    {
        $today = Carbon::now();
        $thresholdDate = Carbon::now()->addDays(15); // do not use $today->addDays(15) it modifies $today

        return ($this->$certificate <= $thresholdDate && $this->$certificate > $today);
    }

    /**
     * Get the truck type of the vehicle
     */
    public function truckType()
    {
        return $this->belongsTo('App\Models\TruckType')->withTrashed();
    }

    /**
    * Get the transportation records associated with the truck.
    */
    public function transportations()
    {
        return $this->hasMany('App\Models\Transportation', 'truck_id');
    }

    /**
    * Get the employeeWage records associated with the truck.
    */
    public function employeeWages()
    {
        return $this->hasManyThrough(
            'App\Models\EmployeeWage',
            'App\Models\Transportation',
            'truck_id',
            'transportation_id',
            'id',
            'id'
        );
    }

    /**
    * Get the expense records associated with the truck.
    */
    public function expenses()
    {
        return $this->hasMany('App\Models\Expense', 'truck_id');
    }

    /**
    * Get the purchase records associated with the truck.
    */
    public function purchases()
    {
        return $this->hasManyThrough(
            'App\Models\Purchase',
            'App\Models\Transportation',
            'truck_id',
            'transportation_id',
            'id',
            'id'
        );
    }

    /**
    * Get the Sale records associated with the truck.
    */
    public function sales()
    {
        return $this->hasManyThrough(
            'App\Models\Sale',
            'App\Models\Transportation',
            'truck_id',
            'transportation_id',
            'id',
            'id'
        );
    }
}
