<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected $repository_name;
    protected $repository;
    protected $repository_scopes = array();
    protected $repository_presenter = null;

    public function __construct(){
        if ($this->repository_name){
            $repository_class = array_get(config('repositories', array()), $this->repository_name);
            
            if ($repository_class){
                $this->repository = app($repository_class);
                foreach ($this->repository_scopes as $index => $scope) {
                    if (is_numeric($index))
                        $this->repository->pushCriteria($scope);
                    else
                        $this->repository->pushCriteria(new $index($scope));
                }

                if ($this->repository_presenter)
                $this->repository->setPresenter($this->repository_presenter);

            }
        }
    }

    protected function attributes(){
        $attributes = request()->input("data");
        return collect(isset($attributes["attributes"]) ? $attributes["attributes"] : array_get($attributes, 'attribute', []));
    }

    protected function pageParams(){
        return collect(empty(request()->only("page")) ? ["limit" => 10, "offset" => 0] : request()->only('page')["page"]);
    }
    
}
