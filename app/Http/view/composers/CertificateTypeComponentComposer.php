<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class CertificateTypeComponentComposer
{
    protected $certificateTypes = [];

    /**
     * Create a new profile composer.
     *
     */
    public function __construct()
    {
        try {
            $this->certificateTypes = config('constants.certificateTypes');
        } catch (Exception $e) {
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('certificateTypes', $this->certificateTypes);
    }
}
