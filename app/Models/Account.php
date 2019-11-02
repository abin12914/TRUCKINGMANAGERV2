<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
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
    protected $dates = ['deleted_at'];

    /**
     * Scope a query to only include active accounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the employee record associated with the account.
     */
    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'account_id')->withTrashed();
    }

    /**
     * Get the debit transaction records associated with the account.
     */
    public function debitTransactions()
    {
        return $this->hasMany('App\Models\Transaction', 'debit_account_id');
    }

    /**
     * Get the credit transaction records associated with the account.
     */
    public function creditTransactions()
    {
        return $this->hasMany('App\Models\Transaction', 'credit_account_id');
    }

    /**
     * Get the sum of debit transaction records associated with the account.
     */
    public function getdebitTransactionsSum()
    {
        return $this->debitTransactions()->sum('amount');
    }
}
