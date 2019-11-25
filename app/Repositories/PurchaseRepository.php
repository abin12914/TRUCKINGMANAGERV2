<?php

namespace App\Repositories;

use App\Models\Purchase;
use Exception;
use App\Exceptions\TMException;

class PurchaseRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.PurchaseRepository');
    }

    /**
     * Return purchases.
     */
    public function getPurchases(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $purchases = [];

        try {
            $purchases = empty($withParams) ? Purchase::query() : Purchase::with($withParams);

            $purchases = $activeFlag ? $purchases->active() : $purchases;

            $purchases = parent::whereFilter($purchases, $whereParams);

            $purchases = parent::orWhereFilter($purchases, $orWhereParams);

            $purchases = parent::relationalFilter($purchases, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($purchases, $aggregates) : parent::getFilter($purchases, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $purchases;
    }

    /**
     * return purchase.
     */
    public function getPurchase($id, $withParams=[], $activeFlag=true)
    {
        $purchase = [];

        try {
            $purchase = empty($withParams) ? Purchase::query() : Purchase::with($withParams);

            $purchase = $activeFlag ? $purchase->active() : $purchase;

            $purchase = $purchase->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $purchase;
    }

    /**
     * Action for purchase save.
     */
    public function savePurchase($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $purchase = Purchase::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $purchase->$key = $value;
            }
            //purchase save
            $purchase->save();

            return [
                'flag'    => true,
                'purchase' => $purchase,
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

    public function deletePurchase($id, $forceFlag=false)
    {
        try {
            //get purchase
            $purchase = $this->getPurchase($id, [], false);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            $forceFlag ? $purchase->forceDelete() : $purchase->delete();

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
