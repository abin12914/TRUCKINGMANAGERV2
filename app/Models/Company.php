<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\CreatedCompanyEvent;

class Company extends Model
{
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
    protected $dates = ['deleted_at'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CreatedCompanyEvent::class,
    ];

    /**
     * Scope a query to only include active companies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the user records associated with the company.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'company_id')->withTrashed();
    }

    /**
     * Get the settings details related to the company
     */
    public function companySettings()
    {
        return $this->belongsTo('App\Models\CompanySettings');
    }
}
