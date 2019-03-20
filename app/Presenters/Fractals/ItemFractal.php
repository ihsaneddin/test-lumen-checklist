<?php
namespace App\Presenters\Fractals;

use League\Fractal\TransformerAbstract;

class ItemFractal extends TransformerAbstract{

  public function transform($item)
  {
      return [
          "type" => "items",
          "id" => $item->getKey(),
          "attributes" => [
              "description" => $item->description,
              "is_completed" => $item->is_completed,
              "completed_at" => $item->completed_at,
              "updated_by" => $item->updated_by,
              "due" => $item->due,
              "urgency" => $item->urgency,
              'created_at'  => $item->created_at->toAtomString(),
              'updated_at'  => $item->updated_at->toAtomString(),  
          ],
          "links"=> [
              "self" => route("checklists.items.show", ["checklist_id" => $item->checklist_id, "id" => $item->getKey()] )
          ]
      ];
  }

}
