<?php

namespace WebId\Flan\Filters\Resources;

use WebId\Flan\Filters\Models\FilterField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterFieldResource extends JsonResource
{
    /** @var FilterField */
    public $resource;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'name' => $this->resource->name,
            'content' => $this->resource->content,
        ];
    }
}
