<?php

namespace Wepa\Procedures\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcedureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->when($this->id, function () {
                return $this->id;
            }),
            'name' => $this->name,
            'position' => $this->position,
            'category_id' => $this->category_id,
            'category' => CategoryResource::make($this->category),
            'files' => $this->files,
            'body' => $this->body,
        ];
    }
}
