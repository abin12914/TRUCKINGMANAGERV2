<?php

namespace App\Repositories;

use App\Models\EmployeeWage;
use Exception;
use App\Exceptions\AppCustomException;

class EmployeeWageRepository extends Repository
{
    public $repositoryCode, $errorCode = 0, $loop = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.EmployeeWageRepository');
    }

    /**
     * Return employeeWages.
     */
    public function getEmployeeWages(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $employeeWages = [];

        try {
            $employeeWages = empty($withParams) ? EmployeeWage::query() : EmployeeWage::with($withParams);

            $employeeWages = $activeFlag ? $employeeWages->active() : $employeeWages;

            $employeeWages = parent::whereFilter($employeeWages, $whereParams);

            $employeeWages = parent::orWhereFilter($employeeWages, $orWhereParams);

            $employeeWages = parent::relationalFilter($employeeWages, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($employeeWages, $aggregates) : parent::getFilter($employeeWages, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employeeWages;
    }

    /**
     * return employeeWage.
     */
    public function getEmployeeWage($id, $withParams=[], $activeFlag=true)
    {
        $employeeWage = [];

        try {
            if(empty($withParams)) {
                $employeeWage = EmployeeWage::query();
            } else {
                $employeeWage = EmployeeWage::with($withParams);
            }
            
            if($activeFlag) {
                $employeeWage = $employeeWage->active();
            }

            $employeeWage = $employeeWage->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 3;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employeeWage;
    }

    /**
     * Action for saving employeeWages.
     */
    public function saveEmployeeWage($inputArray, $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $employeeWage = EmployeeWage::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $employeeWage->$key = $value;
            }
            //employeeWage save
            $employeeWage->save();

            return [
                'flag'    => true,
                'employeeWage' => $employeeWage,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new AppCustomException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 4,
        ];
    }

    public function deleteEmployeeWage($id, $forceFlag=false)
    {
        try {
            $employeeWage = $this->getEmployeeWage($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $employeeWage->forceDelete() : $employeeWage->delete();
            
            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ?  $e->getCode() : $this->repositoryCode + 5);
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }
        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
