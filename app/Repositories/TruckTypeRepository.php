<?php

namespace App\Repositories;

use App\Models\TruckType;
use Exception;
use App\Exceptions\TMException;

class TruckTypeRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.TruckTypeRepository');
    }

    /**
     * Return truckTypes.
     */
    public function getTruckTypes(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $truckTypes = [];

        try {
            $truckTypes = empty($withParams) ? TruckType::query() : TruckType::with($withParams);

            $truckTypes = $activeFlag ? $truckTypes->active() : $truckTypes;

            $truckTypes = parent::whereFilter($truckTypes, $whereParams);

            $truckTypes = parent::orWhereFilter($truckTypes, $orWhereParams);

            $truckTypes = parent::relationalFilter($truckTypes, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($truckTypes, $aggregates): parent::getFilter($truckTypes, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $truckTypes;
    }

    /**
     * return truckType.
     */
    public function getTruckType($id, $withParams=[], $activeFlag=true)
    {
        $truckType = [];

        try {
            $truckType = empty($withParams) ? TruckType::query() : TruckType::with($withParams);

            $truckType = $activeFlag ? $truckType->active() : $truckType;

            $truckType = $truckType->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $truckType;
    }

    /**
     * Action for saving truckTypes.
     */
    public function saveTruckType($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $truckType = TruckType::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $truckType->$key = $value;
            }
            //truckType save
            $truckType->save();

            return [
                'flag'    => true,
                'truckType' => $truckType,
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

    public function deleteTruckType($id, $forceFlag=false)
    {
        try {
            $truckType = $this->getTruckType($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $truckType->forceDelete() : $truckType->delete();

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
