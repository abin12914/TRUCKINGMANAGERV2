<?php

namespace App\Repositories;

use App\Models\Truck;
use Exception;
use App\Exceptions\TMException;

class TruckRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.TruckRepository');
    }

    /**
     * Return trucks.
     */
    public function getTrucks(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $trucks = [];

        try {
            $trucks = empty($withParams) ? Truck::query() : Truck::with($withParams);

            $trucks = $activeFlag ? $trucks->active() : $trucks;

            $trucks = parent::whereFilter($trucks, $whereParams);

            $trucks = parent::orWhereFilter($trucks, $orWhereParams);

            $trucks = parent::relationalFilter($trucks, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($trucks, $aggregates) : parent::getFilter($trucks, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $trucks;
    }

    /**
     * return truck.
     */
    public function getTruck($id, $withParams=[], $activeFlag=true)
    {
        $truck = [];

        try {
            if(empty($withParams)) {
                $truck = Truck::query();
            } else {
                $truck = Truck::with($withParams);
            }

            if($activeFlag) {
                $truck = $truck->active();
            }

            $truck = $truck->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }

            throw new TMException("CustomError", $this->errorCode);
        }

        return $truck;
    }

    /**
     * Action for saving trucks.
     */
    public function saveTruck($inputArray, $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $truck = Truck::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $truck->$key = $value;
            }
            //truck save
            $truck->save();

            return [
                'flag'    => true,
                'truck' => $truck,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);
dd($e);
            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 4,
        ];
    }

    public function deleteTruck($id, $forceFlag=false)
    {
        try {
            $truck = $this->getTruck($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $truck->forceDelete() : $truck->delete();

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
