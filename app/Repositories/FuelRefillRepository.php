<?php

namespace App\Repositories;

use App\Models\FuelRefill;
use Exception;
use App\Exceptions\TMException;

class FuelRefillRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.FuelRefillRepository');
    }

    /**
     * Return fuelRefills.
     */
    public function getFuelRefills(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $fuelRefills = [];

        try {
            $fuelRefills = empty($withParams) ? FuelRefill::query() : FuelRefill::with($withParams);

            $fuelRefills = $activeFlag ? $fuelRefills->active() : $fuelRefills;

            $fuelRefills = parent::whereFilter($fuelRefills, $whereParams);

            $fuelRefills = parent::orWhereFilter($fuelRefills, $orWhereParams);

            $fuelRefills = parent::relationalFilter($fuelRefills, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($fuelRefills, $aggregates) : parent::getFilter($fuelRefills, $orderBy));
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $fuelRefills;
    }

    /**
     * return fuelRefill.
     */
    public function getFuelRefill($id, $withParams=[], $activeFlag=true)
    {
        $fuelRefill = [];

        try {
            $fuelRefill = empty($withParams) ? FuelRefill::query() : FuelRefill::with($withParams);

            $fuelRefill = $activeFlag ? $fuelRefill->active() : $fuelRefill;

            $fuelRefill = $fuelRefill->findOrFail($id);
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $fuelRefill;
    }

    /**
     * Action for fuelRefill save.
     */
    public function saveFuelRefill($inputArray=[], $id=null)
    {
        try {
            //find record with id or create new if none exist
            $fuelRefill = FuelRefill::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $fuelRefill->$key = $value;
            }
            //fuelRefill save
            $fuelRefill->save();

            return [
                'flag'    => true,
                'fuelRefill' => $fuelRefill,
            ];
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 4,
        ];
    }

    public function deleteFuelRefill($id, $forceFlag=false)
    {
        try {
            //get fuelRefill
            $fuelRefill = $this->getFuelRefill($id, [], false);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            $forceFlag ? $fuelRefill->forceDelete() : $fuelRefill->delete();

            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (\Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
