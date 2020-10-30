<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,   
            "ezzycare_card"=>$this->ezzycare_card, 
            "profile_image"=>$this->profile_image, 
            "created_at"=>$this->created_at, 
        ];
    }
}
