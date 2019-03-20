<?php 
namespace App\Entities;

class Checklist extends Model{
    
    protected $table = "checklists";
    protected $fillable = [
        "object_domain", "object_id", "description", "is_completed", "updated_by", "due", "urgency", "items"
    ];


    protected static function boot(){
        static::saving(function($model){
            $model->separateItems();
        });
        static::saved(function($model){
            $model->saveItems();
        });
        parent::boot();
    }

    protected $__items = [];

    protected function saveItems(){
        if(!empty($this->__items)){
            if(is_array($this->__items)){
                foreach($this->__items as $index => $item){
                    $this->items()->create(["description" => $item]);
                }
            }
            $this->__items = [];
        }
    }

    protected function separateItems(){
        if(array_key_exists('items', $this->attributes)){
            $this->__items = $this->items;
            array_forget($this->attributes, "items");
        }
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

}