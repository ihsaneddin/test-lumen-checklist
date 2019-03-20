<?php 
namespace App\Repositories\Scopes;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class OwnedByChecklist implements CriteriaInterface {

    public function apply($model, RepositoryInterface $repository)
    {
        if(request()->route("checklist_id")){
            $model = $model->where('checklist_id','=', request()->route("checklist_id"));
        }
        return $model;
    }
}