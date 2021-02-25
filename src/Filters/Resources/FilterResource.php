<?php

namespace WebId\Flan\Filters\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array<string, string>
     */
    public function toArray($request)
    {
        return (array) $this->resource;
    }
}
