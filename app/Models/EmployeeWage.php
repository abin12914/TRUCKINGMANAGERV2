<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingEmployeeWageEvent;

class EmployeeWage extends Model
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
    protected $dates = ['from_date', 'to_date', 'deleted_at'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => DeletingEmployeeWageEvent::class,
    ];

    /**
     * Scope a query to only include active employeeWages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the employee details related to the wage record
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id')->withTrashed();
    }

    /**
     * Get the transaction details associated with the employee wage
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the transportation details associated with the employee wage
     */
    public function transportation()
    {
        return $this->belongsTo('App\Models\Transportation','transportation_id');
    }
}
