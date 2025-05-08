<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $values = parent::toArray($request);
        $values["vendeur"] = $this->vendeur?->name;
        $values["categorie"] = $this->category?->nom;
        $values["image"] = $this->images;
        $values["owner"] = $this->vendeur;
        return $values;
    } 
    // public static function collection($resource)
    // {
        
    //     return parent::collection($resource)->additional([
    //         "count" => $resource->count()
    //     ]) ;
    // }

}
