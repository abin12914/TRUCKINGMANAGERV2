<?php

namespace App\Repositories;

use App\Models\Transportation;
use Exception;
use App\Exceptions\TMException;

class TransportationRepository extends Repository
{
    public $repositoryCode, $errorCode = 0, $loop = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.TransportationRepository');
    }

    /**
     * Return transportations.
     */
    public function getTransportations(
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

            $transportations = $activeFlag ? $transportations->active() : $transportations;

            $transportations = parent::whereFilter($transportations, $whereParams);

            $transportations = parent::orWhereFilter($transportations, $orWhereParams);

            $transportations = parent::relationalFilter($transportations, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($transportations, $aggregates) : parent::getFilter($transportations, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);
dd($e);
            throw new TMException("CustomError", $this->errorCode);
        }

        return $transportations;
    }

    /**
     * return transportation.
     */
    public function getTransportation($id, $withParams=[], $activeFlag=true)
    {
        $transportation = [];

        try {
            if(empty($withParams)) {
                $transportation = Transportation::query();
            } else {
                $transportation = Transportation::with($withParams);
            }
            
            if($activeFlag) {
                $transportation = $transportation->active();
            }

            $transportation = $transportation->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 3;
            }
            
            throw new TMException("CustomError", $this->errorCode);
        }

        return $transportation;
    }

    /**
     * Action for saving transportations.
     */
    public function saveTransportation($inputArray, $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $transportation = Transportation::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $transportation->$key = $value;
            }
            //transportation save
            $transportation->save();

            return [
                'flag'    => true,
                'transportation' => $transportation,
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

    public function deleteTransportation($id, $forceFlag=false)
    {
        try {
            $transportation = $this->getTransportation($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $transportation->forceDelete() : $transportation->delete();
            
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
