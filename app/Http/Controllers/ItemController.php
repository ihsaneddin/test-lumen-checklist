<?php
namespace App\Http\Controllers;

use App\Repositories\Scopes\OwnedByChecklist;

class ItemController extends Controller
{
    protected $repository_name = "item";
    protected $repository_presenter = \App\Presenters\Item::class;
    protected $repository_scopes = [OwnedByChecklist::class];

    public function index(){
        $items = $this->repository->offset($this->pageParams()->get('limit'), $this->pageParams()->get("offset"));
        return response()->json($items);
    }

    public function store(){
        $item = $this->repository->create($this->itemParams());
        return response()->json($item);
    }

    public function update($id){
        $item = $this->repository->update($this->itemParams(), $id);
        return response()->json($item);
    }
    
    public function destroy($id){
        $item = $this->repository->delete($id);
        return response()->json(null, 204);
    }

    public function complete(){

    }

    public function incomplete(){

    }

    protected function itemParams(){
        $params = $this->attributes()->only(["description", "is_completed", "completed_at", "due", "urgency", "updated_by", "checklist_id"])->toArray();
        $params["checklist_id"] = request()->route("checklist_id");
        return $params;
    }
}
