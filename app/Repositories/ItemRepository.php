<?php 
namespace App\Repositories;

use App\Entities\Item;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ItemRepository extends BaseRepository {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'description' => 'required',
            'due'  => 'date_format:Y-m-d H:i:s',
            'checklist_id' => 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'description' => 'required',
            'checklist_id' => 'required',
            'due'  => 'date_format:Y-m-d H:i:s'
        ]
   ];

    public function model(){
        return Item::class;
    }    

}