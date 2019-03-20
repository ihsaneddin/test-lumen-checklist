<?php 
namespace App\Presenters;

use App\Presenters\Fractals\ItemFractal;

class Item extends Fractal {

    public function getTransformer()
    {
        return new ItemFractal();
    }

}
