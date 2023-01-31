<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $creator = (object)[
            "id"=>$this->user->id,
            "name"=>$this->user->name,
            "role" => $this->user->role,
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'creator' => $creator,
            'date'  => $this->created_at->format('d/M/Y'),
            'time'  => $this->created_at->format('h:i:s A')
        ];
    }
}
