<?php 
namespace App\Presenters;

use App\Presenters\Fractals\ChecklistFractal;

class Checklist extends Fractal {

    public function getTransformer()
    {
        return new ChecklistFractal();
    }

}
