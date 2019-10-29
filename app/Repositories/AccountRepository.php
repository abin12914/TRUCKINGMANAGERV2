<?php

namespace App\Repositories;

use App\Models\Account;
use Exception;
use App\Exceptions\TMException;

class AccountRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.AccountRepository');
    }

    /**
     * Return accounts.
     */
    public function getAccounts(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $accounts = [];

        try {
            $accounts = empty($withParams) ? Account::query() : Account::with($withParams);

            $accounts = $activeFlag ? $accounts->active() : $accounts;

            $accounts = parent::whereFilter($accounts, $whereParams);

            $accounts = parent::orWhereFilter($accounts, $orWhereParams);

            $accounts = parent::relationalFilter($accounts, $relationalParams);

            //if asking aggregates ? return result.
            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($accounts, $aggregates): parent::getFilter($accounts, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $accounts;
    }

    /**
     * return account.
     */
    public function getAccount($id, $withParams=[], $activeFlag=true)
    {
        $account = [];

        try {
            if(empty($withParams)) {
                $account = Account::query();
            } else {
                $account = Account::with($withParams);
            }

            if($activeFlag) {
                $account = $account->active();
            }

            $account = $account->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $account;
    }

    /**
     * Action for saving accounts.
     */
    public function saveAccount($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $account = Account::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $account->$key = $value;
            }
            //account save
            $account->save();

            return [
                'flag'    => true,
                'account' => $account,
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

    public function deleteAccount($id, $forceFlag=false)
    {
        try {
            $account = $this->getAccount($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $account->forceDelete() : $account->delete();

            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);

            throw new TMException("CustomError", $this->errorCode);
        }

        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 6,
        ];
    }
}
