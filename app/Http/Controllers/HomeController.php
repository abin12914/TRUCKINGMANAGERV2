<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CompanySettingsRepository;
use Carbon\Carbon;

class HomeController extends Controller
{
    public $errorHead = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
        $this->errorHead = config('settings.controller_code.HomeController');
    }

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('home');
    }

    /**
     * update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request, CompanySettingsRepository $companySettingsRepo)
    {
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
                $inputData['status'] = 1;

                $response = $companySettingsRepo->saveCompanySettings($inputData, $settings->id);
            }

            if($response['flag']) {
                return [
                        "flag"      => true,
                        "message"   => "Settings details updated successfully.",
                    ];
            }
        } catch (\Exception $e) {

        }

        return [
            "flag"      => true,
            "message"   => "Failed to update the settings.",
        ];
    }
}
