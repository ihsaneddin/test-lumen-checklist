<?php 
namespace App\Entities;

class Item extends Model{

    protected $table = "items";
    protected $fillable = [
        "checklist_id", "description", "is_completed", "completed_at", "due", "urgency", "updated_by", "checklist_id"
    ];

    public function checklist(){
        return $this->belongsTo(Checklist::class);
    }

}