<?php

namespace App\Repositories;

use App\Models\CompanySettings;
use Exception;
use App\Exceptions\TMException;

class CompanySettingsRepository extends Repository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.CompanySettingsRepository');
    }

    /**
     * Return companySettings.
     */
    public function getCompanySettings($whereParams)
    {
        $companySettings = [];

        try {
            $companySettings = CompanySettings::query();

            $companySettings = parent::whereFilter($companySettings, $whereParams);

            return parent::getFilter($companySettings, ['by' => 'id', 'order' => 'asc', 'num' => 1]);
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 1);

            throw new TMException("CustomError", $this->errorCode);
        }

        return $companySettings;
    }

    /**
     * Action for saving companySettings.
     */
    public function saveCompanySettings($inputArray=[], $id=null)
    {
        $saveFlag   = false;

        try {
            //find record with id or create new if none exist
            $companySettings = CompanySettings::findOrNew($id);

            foreach ($inputArray as $key => $value) {
                $companySettings->$key = $value;
            }
            //companySettings save
            $companySettings->save();

            return [
                'flag'    => true,
                'companySettings' => $companySettings,
            ];
        } catch (Exception $e) {
            $this->errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : $this->repositoryCode + 3);

            throw new TMException("CustomError", $this->errorCode);
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 2,
        ];
    }
}
