<?php 
namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as Repository;
use Closure;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Contracts\PresenterInterface;
use Prettus\Repository\Contracts\RepositoryCriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Traits\ComparesVersionsTrait;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

use App\Entities\Model as Base;

class BaseRepository extends Repository{

    public function model(){
        return Base::class;
    }
    

    public function update(array $attributes, $id)
    {
        $this->applyScope();
        $this->applyCriteria();

        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.\
            $model_class = $this->model();
            if( $this->versionCompare($this->app->version(), "5.2.*", ">") ){
                $model = new $model_class;
                $attributes = $model->forceFill($attributes)->makeVisible($model->getHidden())->toArray();
            }else{
                $model = (new $model_class)->forceFill($attributes);
                $model->addVisible($this->model->getHidden());
                $attributes = $model->toArray();
            }

            $this->validator->with($attributes)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    public function offset($limit = 10 , $offset = 0){
        $this->applyCriteria();
        $this->applyScope();
        $total = $this->model->count();
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 0 : $offset;
        $results = $this->model->offset($offset)->limit($limit);
        $this->resetModel();
        $data  =$this->parserResult($results->get());
        return array_merge($data, $this->offsetMetaParams($results, $offset, $limit));
    }

    protected function offsetMetaParams($results, $offset, $limit){
        $query = request()->query();
        $first_page = array_replace_recursive($query, ["page" => ["offset" => 0, "limit" => $limit]]);
        $first_page = request()->fullUrlWithQuery($first_page);
        $next_page = array_replace_recursive($query, ["page" => ["offset" => $offset + 1, "limit" => $limit]]);
        $next_page = request()->fullUrlWithQuery($next_page);
        $prev_page = array_replace_recursive($query, ["page" => ["offset" => $offset - $limit > 0 ? $offset - $limit : 0, "limit" => $limit]]);
        $prev_page = request()->fullUrlWithQuery($prev_page);
        $last_page =  array_replace_recursive($query, ["page" => ["offset" => $results->count() - $limit > 0 ? ($results->count() - $limit + 1) : 0, "limit" => $limit]]);
        $last_page = request()->fullUrlWithQuery($last_page);
        return [
            "meta" => [
              "total" => $results->count(),
              "count" => $results->get()->count()
            ],
            "links" => [
                "first" => $first_page,
                "previous" => $prev_page,
                "next" => $next_page,
                "last" => $last_page
            ]
        ];
    }

}