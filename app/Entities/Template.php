<?php 
namespace App\Entities;

class Template extends Model{

    protected $casts = [ "checklist" => "array", "items" => "array" ];


}