<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\TruckRepository;
use \Carbon\Carbon;
use Exception;

class CertificateDetailsComposer
{
    protected $expiredCertTrucks = [], $criticalCertTrucks = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(TruckRepository $truckRepo)
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

        $this->expiredCertTrucks = $trucks->filter(function ($truck, $key) {
            return $truck->isCertExpired('insurance_upto')
             || $truck->isCertExpired('tax_upto')
             || $truck->isCertExpired('fitness_upto')
             || $truck->isCertExpired('permit_upto')
             || $truck->isCertExpired('pollution_upto');
        });

        $this->criticalCertTrucks = $trucks->filter(function ($truck, $key) {
            return ($truck->isCertCritical('insurance_upto')
             || $truck->isCertCritical('tax_upto')
             || $truck->isCertCritical('fitness_upto')
             || $truck->isCertCritical('permit_upto')
             || $truck->isCertCritical('pollution_upto'));
        });
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'criticalCertTrucks' => $this->criticalCertTrucks,
            'expiredCertTrucks'  => $this->expiredCertTrucks
        ]);
    }
}
