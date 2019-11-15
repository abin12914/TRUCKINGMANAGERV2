<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelRefill extends Model
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
    protected $dates = ['deleted_at', 'refill_date'];

    /**
     * Scope a query to only include active expense records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the truck details associated with the expense
     */
    public function truck()
    {
        return $this->belongsTo('App\Models\Truck')->withTrashed();
    }

    /**
     * Get the transaction details associated with the expense
     */
    public function expense()
    {
        return $this->belongsTo('App\Models\Expense','expense_id');
    }
}
