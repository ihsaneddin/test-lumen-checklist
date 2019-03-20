<?php
namespace App\Presenters\Fractals;

use League\Fractal\TransformerAbstract;

class ChecklistFractal extends TransformerAbstract{

  public function transform($checklist)
  {
      return [
          "type" => "checklists",
          "id" => $checklist->getKey(),
          "attributes" => [
              "object_domain" => $checklist->object_domain,
              "object_id" => $checklist->object_id,
              "description" => $checklist->description,
              "is_completed" => $checklist->is_completed,
              "updated_by" => $checklist->updated_by,
              "due" => $checklist->due,
              "urgency" => $checklist->urgency,
              'created_at'  => $checklist->created_at->toAtomString(),
              'updated_at'  => $checklist->updated_at->toAtomString(),  
          ],
          "links"=> [
              "self" => route("checklists.show", ["id" => $checklist->getKey()] )
          ]
      ];
  }

}
