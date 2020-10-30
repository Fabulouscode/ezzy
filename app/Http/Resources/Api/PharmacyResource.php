<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyResource extends JsonResource
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
            "profile_image"=>$this->profile_image, 
            "created_at"=>$this->created_at, 
            "monthly_wallet_balance" => $this->monthly_wallet_balance,    
            "total_wallet_balance" => $this->total_wallet_balance,    
            "user_cancelled_order" => $this->user_cancelled_order,    
            "user_active_order" => $this->user_active_order,    
            "user_completed_order"=>$this->user_completed_order
        ];
    }
}
