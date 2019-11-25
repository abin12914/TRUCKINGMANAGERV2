<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CompanySettingsRepository;
use Carbon\Carbon;

class CompanySettingsController extends Controller
{
    public $errorHead = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->errorHead = config('settings.controller_code.CompanySettingsController');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('company-settings.edit-add');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanySettingsRepository $companySettingsRepo, Request $request)
    {
        $errorCode = 0;
        $inputData = [];
        $response['flag'] = null;

        try {
            $settings = $companySettingsRepo->getCompanySettings([]);

            if(!empty($settings)) {
                $settings = $settings->first();

                if($request->has('default_date')) {
                    $inputData['default_date'] = !empty($request->get('default_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('default_date'))->format('Y-m-d') : null;
                }
                if($request->has('driver_auto_selection')) {
                    $inputData['driver_auto_selection'] = $request->get('driver_auto_selection');
                }
                if($request->has('contractor_auto_selection')) {
                    $inputData['contractor_auto_selection'] = $request->get('contractor_auto_selection');
                }
                if($request->has('measurements_auto_selection')) {
                    $inputData['measurements_auto_selection'] = $request->get('measurements_auto_selection');
                }
                if($request->has('purchase_auto_selection')) {
                    $inputData['purchase_auto_selection'] = $request->get('purchase_auto_selection');
                }
                if($request->has('sale_auto_selection')) {
                    $inputData['sale_auto_selection'] = $request->get('sale_auto_selection');
                }
                $inputData['status'] = 1;

                $response = $companySettingsRepo->saveCompanySettings($inputData, $settings->id);
            }

            if($request->ajax()){
                if($response['flag']) {
                    return [
                            "flag"    => true,
                            "message" => "Settings details updated successfully.",
                        ];
                }
            }

            return redirect()->back()->with("message","Settings updated successfully.")->with("alert-class", "success");
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);
        }

        if($request->ajax()){
            return [
                "flag"      => false,
                "message"   => "Failed to update the settings. #". $this->errorHead. "/". $errorCode,
            ];
        }

        return redirect()->back()->with("message","Failed to save the account details. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
