<?php

namespace App\Repositories;

use App\Models\Site;
use Exception;
use App\Exceptions\TMException;

class SiteRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.SiteRepository');
    }

    /**
     * Return sites.
     */
    public function getSites(
        $whereParams=[],
        $orWhereParams=[],
        $relationalParams=[],
        $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],
        $aggregates=['key' => null, 'value' => null],
        $withParams=[],
        $activeFlag=true
    ){
        $sites = [];

        try {
            $sites = empty($withParams) ? Site::query() : Site::with($withParams);

            $sites = $activeFlag ? $sites->active() : $sites;

            $sites = parent::whereFilter($sites, $whereParams);

            $sites = parent::orWhereFilter($sites, $orWhereParams);

            $sites = parent::relationalFilter($sites, $relationalParams);

            return (!empty($aggregates['key']) ? parent::aggregatesSwitch($sites, $aggregates) : parent::getFilter($sites, $orderBy));
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $sites;
    }

    /**
     * return site.
     */
    public function getSite($id, $withParams=[], $activeFlag=true)
    {
        $site = [];

        try {
            if(empty($withParams)) {
                $site = Site::query();
            } else {
                $site = Site::with($withParams);
            }

            if($activeFlag) {
                $site = $site->active();
            }

            $site = $site->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }

            throw new TMException("CustomError", $this->errorCode);
        }

        return $site;
    }

    /**
     * Action for saving sites.
     */
    public function saveSite($inputArray, $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $site = Site::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $site->$key = $value;
            }
            //site save
            $site->save();

            return [
                'flag'    => true,
                'site' => $site,
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

    public function deleteSite($id, $forceFlag=false)
    {
        try {
            $site = $this->getSite($id, [], false);

            //force delete or soft delete
            //related records will be deleted by deleting event handlers
            $forceFlag ? $site->forceDelete() : $site->delete();

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
