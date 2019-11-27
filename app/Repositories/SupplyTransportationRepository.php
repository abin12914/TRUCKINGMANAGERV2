<?php

namespace App\Repositories;

use App\Models\Transportation;
use Exception;
use App\Exceptions\TMException;

class SupplyTransportationRepository extends Repository
{
    public $repositoryCode, $errorCode = 0, $loop = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.SupplyTransportationRepository');
    }

    /**
     * Return transportations.
     */
    public function getSupplyTransportations(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $transportations = [];

        try {
            $transportations = empty($withParams) ? Transportation::query() : Transportation::with($withParams);

            //transportations which has reated sale and purchase are supply
            $transportations = $transportations->has('purchase')->has('sale');

            $transportations = $activeFlag ? $transportations->active() : $transportations;

            $transportations = parent::whereFilter($transportations, $whereParams);

            $transportations = parent::orWhereFilter($transportations, $orWhereParams);

            $transportations = parent::relationalFilter($transportations, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($transportations, $aggregates) : parent::getFilter($transportations, $orderBy));
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $transportations;
    }

    /**
     * return transportation.
     */
    public function getSupplyTransportation($id, $withParams=[], $activeFlag=true)
    {
        $transportation = [];

        try {
            $transportation = empty($withParams) ? Transportation::query() : Transportation::with($withParams);

            //transportations which has reated sale and purchase are supply
            $transportation = $transportation->has('purchase')->has('sale');

            $transportation = $activeFlag ? $transportation->active() : $transportation;

            $transportation = $transportation->findOrFail($id);
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $transportation;
    }
}
