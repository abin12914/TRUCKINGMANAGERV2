<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exceptions\TMException;
use App\Repositories\CompanySettingsRepository;

class Controller extends BaseController
{
    //company settings
    public $errorHead, $companySettings = [];

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function companySettings()
    {
        $this->errorHead = config('settings.controller_code.Controller');
        try {
            $this->companySettings = (new CompanySettingsRepository)->getCompanySettings([])->first();
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->errorHead);
        }
    }
}
