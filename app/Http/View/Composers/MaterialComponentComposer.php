<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\MaterialRepository;

class MaterialComponentComposer
{
    protected $materialRepo, $materials = [];

    /**
     * Create a new materials partial composer.
     *
     * @param  MaterialRepository  $materials
     * @return void
     */
    public function __construct(MaterialRepository $materialRepo)
    {
        $this->materialRepo = $materialRepo;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            $this->materials = $this->materialRepo->getMaterials(
                [], [],  [], ['by' => 'name', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (\Exception $e) {
        }

        $view->with('materialsCombo', $this->materials);
    }
}
