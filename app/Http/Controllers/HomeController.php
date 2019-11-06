<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TruckRepository;
use \Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
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
    public function dashboard(TruckRepository $truckRepo)
    {
        $thresholdDate = Carbon::now()->addDays(15);

        $whereParams = [
            'ownership_status' => [
                'paramName'     => 'ownership_status',
                'paramOperator' => '=',
                'paramValue'    => 1, //own trucks only
            ]
        ];

        $orWhereParams = [
            'insurance_upto' => [
                'paramName'     => 'insurance_upto',
                'paramOperator' => '<=',
                'paramValue'    => $thresholdDate,
            ],
            'tax_upto' => [
                'paramName'     => 'tax_upto',
                'paramOperator' => '<=',
                'paramValue'    => $thresholdDate,
            ],
            'fitness_upto' => [
                'paramName'     => 'fitness_upto',
                'paramOperator' => '<=',
                'paramValue'    => $thresholdDate,
            ],
            'permit_upto' => [
                'paramName'     => 'permit_upto',
                'paramOperator' => '<=',
                'paramValue'    => $thresholdDate,
            ],
            'pollution_upto' => [
                'paramName'     => 'pollution_upto',
                'paramOperator' => '<=',
                'paramValue'    => $thresholdDate,
            ],
        ];

        $trucks = $truckRepo->getTrucks(
            $whereParams, $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true
        );

        $expiredCertTrucks = $trucks->filter(function ($value, $key) {
            return $value->isCertExpired('insurance_upto')
             || $value->isCertExpired('tax_upto')
             || $value->isCertExpired('fitness_upto')
             || $value->isCertExpired('permit_upto')
             || $value->isCertExpired('pollution_upto');
        });

        $criticalCertTrucks = $trucks->filter(function ($value, $key) {
            return (!$value->isCertExpired('insurance_upto') && $value->isCertCritical('insurance_upto'))
             || (!$value->isCertExpired('tax_upto') && $value->isCertCritical('tax_upto'))
             || (!$value->isCertExpired('fitness_upto') && $value->isCertCritical('fitness_upto'))
             || (!$value->isCertExpired('permit_upto') && $value->isCertCritical('permit_upto'))
             || (!$value->isCertExpired('pollution_upto') && $value->isCertCritical('pollution_upto'));
        });

        return view('home', compact('criticalCertTrucks', 'expiredCertTrucks'));
    }
}
