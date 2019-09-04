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
     * Return services.
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
        $services = [];

        try {
            $services = empty($withParams) ? TruckType::query() : TruckType::with($withParams);

            $services = $activeFlag ? $services->active() : $services;

            $services = parent::whereFilter($services, $whereParams);

            $services = parent::orWhereFilter($services, $orWhereParams);

            $services = parent::relationalFilter($services, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($services, $aggregates): parent::getFilter($services, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $services;
    }

    /**
     * return service.
     */
    public function getTruckType($id, $withParams=[], $activeFlag=true)
    {
        $service = [];

        try {
            if(empty($withParams)) {
                $service = TruckType::query();
            } else {
                $service = TruckType::with($withParams);
            }
            
            if($activeFlag) {
                $service = $service->active();
            }

            $service = $service->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);
            
            throw new TMException("CustomError", $this->errorCode);
        }

        return $service;
    }

    /**
     * Action for saving services.
     */
    public function saveTruckType($inputArray=[], $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $service = TruckType::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $service->$key = $value;
            }
            //service save
            $service->save();

            return [
                'flag'    => true,
                'service' => $service,
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
        $deleteFlag = false;

        try {
            $service = $this->getTruckType($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $service->forceDelete() : $service->delete();
            
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
