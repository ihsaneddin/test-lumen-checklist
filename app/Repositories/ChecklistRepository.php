<?php 
namespace App\Repositories;

use App\Entities\Checklist;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ChecklistRepository extends BaseRepository {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'description' => 'required',
            'due'  => 'date_format:Y-m-d H:i:s'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'description' => 'required',
            'due'  => 'date_format:Y-m-d H:i:s'
        ]
   ];

    public function model(){
        return Checklist::class;
    }    

}