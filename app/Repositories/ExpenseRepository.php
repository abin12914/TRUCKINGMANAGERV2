<?php

namespace App\Repositories;

use App\Models\Expense;
use Exception;
use App\Exceptions\TMException;

class ExpenseRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.ExpenseRepository');
    }

    /**
     * Return expenses.
     */
    public function getExpenses(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $expenses = [];

        try {
            $expenses = empty($withParams) ? Expense::query() : Expense::with($withParams);

            $expenses = $activeFlag ? $expenses->active() : $expenses;

            $expenses = parent::whereFilter($expenses, $whereParams);

            $expenses = parent::orWhereFilter($expenses, $orWhereParams);

            $expenses = parent::relationalFilter($expenses, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($expenses, $aggregates) : parent::getFilter($expenses, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $expenses;
    }

    /**
     * return expense.
     */
    public function getExpense($id, $withParams=[], $activeFlag=true)
    {
        $expense = [];

        try {
            $expense = empty($withParams) ? Expense::query() : Expense::with($withParams);

            $expense = $activeFlag ? $expense->active() : $expense;

            $expense = $expense->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $expense;
    }

    /**
     * Action for expense save.
     */
    public function saveExpense($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $expense = Expense::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $expense->$key = $value;
            }
            //expense save
            $expense->save();

            return [
                'flag'    => true,
                'expense' => $expense,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 4,
        ];
    }

    public function deleteExpense($id, $forceFlag=false)
    {
        try {
            //get expense
            $expense = $this->getExpense($id, [], false);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            $forceFlag ? $expense->forceDelete() : $expense->delete();

            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
