<?php

namespace App\Repositories;

use App\Models\Employee;
use Exception;
use App\Exceptions\TMException;

class EmployeeRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.EmployeeRepository');
    }

    /**
     * Return employees.
     */
    public function getEmployees(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $employees = [];

        try {
            $employees = empty($withParams) ? Employee::query() : Employee::with($withParams);

            $employees = $activeFlag ? $employees->active() : $employees;

            $employees = parent::whereFilter($employees, $whereParams);

            $employees = parent::orWhereFilter($employees, $orWhereParams);

            $employees = parent::relationalFilter($employees, $relationalParams);

            //if asking aggregates ? return result.
            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($employees, $aggregates) : parent::getFilter($employees, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $employees;
    }

    /**
     * return employee.
     */
    public function getEmployee($id, $withParams=[], $activeFlag=true)
    {
        $employee = [];

        try {
            $employee = empty($withParams) ? Employee::query() : Employee::with($withParams);

            $employee = $activeFlag ? $employee->active() : $employee;

            $employee = $employee->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $employee;
    }

    /**
     * Action for saving employees.
     */
    public function saveEmployee($inputArray, $id=null)
    {
        try {
            //find record with id or create new if none exist
            $employee = Employee::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $employee->$key = $value;
            }
            //employee save
            $employee->save();

            return [
                'flag'     => true,
                'employee' => $employee,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 4,
        ];
    }

    public function deleteEmployee($id, $forceFlag=false)
    {
        try {
            //get employee record
            $employee = $this->getEmployee($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $employee->forceDelete() : $employee->delete();

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
            'error_code'    => $this->repositoryCode + 6,
        ];
    }
}
