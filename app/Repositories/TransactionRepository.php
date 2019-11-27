<?php

namespace App\Repositories;

use App\Models\Transaction;
use Exception;
use App\Exceptions\TMException;

class TransactionRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode       = config('settings.repository_code.TransactionRepository');
        $this->transactionRelations = config('constants.transactionRelations');
    }

    /**
     * Return transactions.
     */
    public function getTransactions(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $relation,
        $activeFlag=true
    ){
        $transactions = [];

        try {
            $transactions = empty($withParams) ? Transaction::query() : Transaction::with($withParams);

            $transactions = $activeFlag ? $transactions->active() : $transactions;

            $transactions = parent::whereFilter($transactions, $whereParams);

            $transactions = parent::orWhereFilter($transactions, $orWhereParams);

            $transactions = parent::relationalFilter($transactions, $relationalParams);

            //has relation checking
            $transactions = (!empty($relation) ? $transactions->has($this->transactionRelations[$relation]['relationName']) : $transactions);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($transactions, $aggregates) : parent::getFilter($transactions, $orderBy));
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $transactions;
    }

    /**
     * return account.
     */
    public function getTransaction($id, $withParams=[], $activeFlag=true)
    {
        $transaction = [];

        try {
            $transaction = empty($withParams) ? Transaction::query() : Transaction::with($withParams);

            $transaction = $activeFlag ? $transaction->active() : $transaction;

            $transaction = $transaction->findOrFail($id);
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $transaction;
    }

    /**
     * Action for saving transaction.
     */
    public function saveTransaction($inputArray, $id=null)
    {
        try {
            //find first record or create new if none exist
            $transaction = Transaction::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $transaction->$key = $value;
            }
            //transaction save
            $transaction->save();

            return [
                'flag'        => true,
                'transaction' => $transaction,
            ];
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new TMException("CustomError", $this->errorCode);
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 4,
        ];
    }

    public function deleteTransaction($id, $forceFlag=false)
    {
        try {
            $transaction = $this->getTransaction($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $transaction->forceDelete() : $transaction->delete();

            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);

            throw new TMException("CustomError", $this->errorCode);
        }

        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 6,
        ];
    }

    /**
     * Return transactions.
     */
    public function getTransactionReport($fromDate, $toDate, $truckId)
    {
        $transactions = [];

        try {
            $transactions = Transaction::with(
                'transportation',
                'employeeWage',
                'purchase',
                'sale',
                'expense'
            );

            $transactions = $transactions->active()->whereBetween('transaction_date', [$fromDate, $toDate]);
            $transactions = $transactions->whereHas('transportation', function ($query) use($truckId) {
                $query->where('truck_id', '=', $truckId);
            })->orWhereHas('employeeWage.transportation', function ($query) use($truckId) {
                $query->where('truck_id', '=', $truckId);
            })->orWhereHas('purchase.transportation', function ($query) use($truckId) {
                $query->where('truck_id', '=', $truckId);
            })->orWhereHas('sale.transportation', function ($query) use($truckId) {
                $query->where('truck_id', '=', $truckId);
            })->orWhereHas('expense', function ($query) use($truckId) {
                $query->where('truck_id', '=', $truckId);
            });
            $transactions = $transactions->get();
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 7);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $transactions;
    }
}
