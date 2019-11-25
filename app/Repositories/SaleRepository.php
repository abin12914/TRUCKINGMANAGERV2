<?php

namespace App\Repositories;

use App\Models\Sale;
use Exception;
use App\Exceptions\TMException;

class SaleRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.SaleRepository');
    }

    /**
     * Return sales.
     */
    public function getSales(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $sales = [];

        try {
            $sales = empty($withParams) ? Sale::query() : Sale::with($withParams);

            $sales = $activeFlag ? $sales->active() : $sales;

            $sales = parent::whereFilter($sales, $whereParams);

            $sales = parent::orWhereFilter($sales, $orWhereParams);

            $sales = parent::relationalFilter($sales, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($sales, $aggregates) : parent::getFilter($sales, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $sales;
    }

    /**
     * return sale.
     */
    public function getSale($id, $withParams=[], $activeFlag=true)
    {
        $sale = [];

        try {
            $sale = empty($withParams) ? Sale::query() : Sale::with($withParams);

            $sale = $activeFlag ? $sale->active() : $sale;

            $sale = $sale->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $sale;
    }

    /**
     * Action for sale save.
     */
    public function saveSale($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $sale = Sale::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $sale->$key = $value;
            }
            //sale save
            $sale->save();

            return [
                'flag'    => true,
                'sale' => $sale,
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

    public function deleteSale($id, $forceFlag=false)
    {
        try {
            //get sale
            $sale = $this->getSale($id, [], false);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            $forceFlag ? $sale->forceDelete() : $sale->delete();

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
