<?php
namespace App\Http\Controllers;

class ChecklistController extends Controller
{
    protected $repository_name = "checklist";
    protected $repository_presenter = \App\Presenters\Checklist::class;

    public function index(){
        $checklists = $this->repository->offset($this->pageParams()->get('limit'), $this->pageParams()->get("offset"));
        return response()->json($checklists);
    }

    public function store(){
        $checklist = $this->repository->create($this->checklistParams());
        return response()->json($checklist);
    }

    public function update($id){
        $checklist = $this->repository->update($this->checklistParams(), $id);
        return response()->json($checklist);
    }
    
    public function destroy($id){
        $checklist = $this->repository->delete($id);
        return response()->json(null, 204);
    }

    protected function checklistParams(){
        return $this->attributes()->only(["object_domain", "object_id", "description", "is_completed", "updated_by", "due", "urgency", "items"])->toArray();
    }
}
