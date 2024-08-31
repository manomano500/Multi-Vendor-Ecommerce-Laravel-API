<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'price' => $this->price,
           'quantity' => $this->quantity,
           'image'=>$this->images->map(function($image){
               return [
                   'id'=>$image->id,
                  'image'=> $image->getImageUrlAttribute()];
           }),
//'image'=>$this->images()->first()?->getImageUrlAttribute() ?? null,
           'category' => $this->category->name,
           'store' => $this->store->name,

           'created_at' => $this->created_at->diffForHumans(),
       ];
    }
}
