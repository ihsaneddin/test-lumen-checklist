<?php
namespace App\Presenters;

use Prettus\Repository\Presenter\FractalPresenter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Fractal extends FractalPresenter {

  abstract public function getTransformer();

  public function present($data)
  {
    #dd($data->fragment("page", 1));
    $res = parent::present($data);
    if($data instanceOf LengthAwarePaginator){
      $res= array_merge($this->customResponseParams($data), ["data" =>$res["data"]]);
    }
    return $res; 
  }

  protected function customResponseParams($data){
    if ($data instanceOf LengthAwarePaginator)
    {
      return [
        "meta" => [
          "count" => count($data->items()),
          "total" => $data->total()
        ],
        "links" => [
          "first" => $data->url(1),
          "last" => $data->url($data->lastPage()),
          "next" => $data->nextPageUrl(), 
          "prev" => $data->previousPageUrl()
        ]
      ];
    }
    if($data instanceOf Collection){
      
    }  
  }

}
