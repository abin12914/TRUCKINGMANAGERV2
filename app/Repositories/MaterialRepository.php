<?php

namespace App\Repositories;

use App\Models\Material;
use Exception;
use App\Exceptions\TMException;

class MaterialRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.MaterialRepository');
    }

    /**
     * Return materials.
     */
    public function getMaterials(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $materials = [];

        try {
            $materials = empty($withParams) ? Material::query() : Material::with($withParams);

            $materials = $activeFlag ? $materials->active() : $materials;

            $materials = parent::whereFilter($materials, $whereParams);

            $materials = parent::orWhereFilter($materials, $orWhereParams);

            $materials = parent::relationalFilter($materials, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($materials, $aggregates): parent::getFilter($materials, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);
dd($e);
            throw new TMException("CustomError", $this->errorCode);
        }

        return $materials;
    }

    /**
     * return material.
     */
    public function getMaterial($id, $withParams=[], $activeFlag=true)
    {
        $material = [];

        try {
            if(empty($withParams)) {
                $material = Material::query();
            } else {
                $material = Material::with($withParams);
            }

            if($activeFlag) {
                $material = $material->active();
            }

            $material = $material->findOrFail($id);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 2);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $material;
    }

    /**
     * Action for saving materials.
     */
    public function saveMaterial($inputArray=[], $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $material = Material::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $material->$key = $value;
            }
            //material save
            $material->save();

            return [
                'flag'    => true,
                'material' => $material,
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

    public function deleteMaterial($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            $material = $this->getMaterial($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $material->forceDelete() : $material->delete();

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
