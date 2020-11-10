<?php

namespace App\Http\Resources;
use App\Http\Resources\BrandResource;
use App\Http\Resources\SubcategoryResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'codeno' => $this->codeno , 
            'name' => $this->name , 
            'photo' => $this->photo , 
            'price' => $this->price, 
            'discount' => $this->discount , 
            'description' => $this->description , 
            'brand' => new BrandResource($this->brand),
            'subcategory' => new SubcategoryResource($this->subcategory),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
