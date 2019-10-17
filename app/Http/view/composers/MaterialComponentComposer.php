<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\MaterialRepository;
use Exception;

class MaterialComponentComposer
{
    protected $materials = [];

    /**
     * Create a new materials partial composer.
     *
     * @param  MaterialRepository  $materials
     * @return void
     */
    public function __construct(MaterialRepository $materialRepo)
    {
        try {
            //getMaterials($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->materials = $materialRepo->getMaterials([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);
        } catch (Exception $e) {
dd($e);
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
        $view->with([
            'materialsCombo' => $this->materials
        ]);
    }
}
