<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_history extends Model
{
    use HasFactory;

    public $chat_type_value = array(
        '0' => 'ePrescibe',
        '1' => 'eRecommendation',
        '2' => 'eDiagnostics',
        '3' => 'Treatment Plan'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'recommended_id',
        'chat_type',
        'plan_name',
        'treatment_name',
    ];

    protected $appends = ['chat_type_name'];

    public function getChatTypeNameAttribute() {
        return array_key_exists($this->chat_type, $this->chat_type_value) ? $this->chat_type_value[$this->chat_type]: '';
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function client() {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }
   
    public function recommended() {
        return $this->hasOne('App\Models\User', 'id', 'recommended_id');
    }

    public function chatDetails() {
        return $this->hasMany('App\Models\Chat_eservices', 'chat_history_id', 'id');
    }

    public function EDignosticsFormat(){
        return [
            'id'=>$this->id,         
            'chat_type'=>$this->chat_type,
            'chat_type_name'=>$this->chat_type_name,
            'services'=> !empty($this->chatDetails) ? $this->getServiceNames($this->chatDetails) :'',
            'client'=>(!empty($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                            ]:'',
            'client'=>(!empty($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                            ]:'',
            'user'=>(!empty($this->user))?
                            [
                                'id'=>$this->user->id,
                                'user_name'=>$this->user->user_name,
                                'profile_image'=>$this->user->profile_image,
                                'address'=>(!empty($this->user->userDetails)) ? $this->user->userDetails->address : '',
                                'eduction_details'=>(!empty($this->user->user_eduction_details)) ? $this->user->user_eduction_details : ''
                            ]:'',
            'recommended'=>(!empty($this->recommended))?
                            [
                                'id'=>$this->recommended->id,
                                'user_name'=>$this->recommended->user_name,
                                'profile_image'=>$this->recommended->profile_image,
                                'address'=>(!empty($this->recommended->userDetails)) ? $this->recommended->userDetails->address : '',
                             ]:'',
        ];
    }

    public function getServiceNames($chatDetails){
        $data = [];
        if(!empty($chatDetails)){
            foreach ($chatDetails as $key => $value) {
                $data[]=[
                        'service_name'=> !empty($value->userService->service) ? $value->userService->service->service_name : '',
                        'service_charge'=> !empty($value->userService) ? $value->userService->service_charge : '',
                    ];
            }
        }
        return $data;
    }

    
    public function EPrescibeFormat(){
        return [
            'id'=>$this->id,         
            'chat_type'=>$this->chat_type,
            'chat_type_name'=>$this->chat_type_name,
            'medicines'=> !empty($this->chatDetails) ? $this->getChatMedicineNames($this->chatDetails) :'',
            'client'=>(!empty($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                            ]:'',
            'client'=>(!empty($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                            ]:'',
            'user'=>(!empty($this->user))?
                            [
                                'id'=>$this->user->id,
                                'user_name'=>$this->user->user_name,
                                'profile_image'=>$this->user->profile_image,
                                'address'=>(!empty($this->user->userDetails)) ? $this->user->userDetails->address : '',
                                'eduction_details'=>(!empty($this->user->user_eduction_details)) ? $this->user->user_eduction_details : ''
                            ]:'',
            'recommended'=>(!empty($this->recommended))?
                            [
                                'id'=>$this->recommended->id,
                                'user_name'=>$this->recommended->user_name,
                                'profile_image'=>$this->recommended->profile_image,
                                'address'=>(!empty($this->recommended->userDetails)) ? $this->recommended->userDetails->address : '',
                             ]:'',
        ];
    }

    public function getChatMedicineNames($chatDetails){
        $data = [];
        if(!empty($chatDetails)){
            foreach ($chatDetails as $key => $value) {
                $data[]=[
                        'id'=> (isset($value->shopMedicineDetails->id)) ? $value->shopMedicineDetails->id : '',
                        'medicine_name'=> (isset($value->shopMedicineDetails->medicineDetails)) ? $value->shopMedicineDetails->medicineDetails->medicine_name : '',
                        'effective_date'=> (isset($value->effective_date)) ? $value->effective_date : '',
                        'patient_direction'=> (isset($value->patient_direction)) ? $value->patient_direction : '',
                        'dispense'=> (isset($value->dispense)) ? $value->dispense : '',
                        'dispense_unit'=> (isset($value->dispense_unit)) ? $value->dispense_unit : '',
                        'refills'=> (isset($value->refills)) ? $value->refills : '',
                        'days_supply'=> (isset($value->days_supply)) ? $value->days_supply : '',
                ];
            }
        }
        return $data;
    }

    public function TreatmentPlanFormat(){
        return [
            'id'=>$this->id,         
            'chat_type'=>$this->chat_type,
            'chat_type_name'=>$this->chat_type_name,
            'treatment_name'=>$this->treatment_name,
            'medicines'=> !empty($this->chatDetails) ? $this->getMedicineNames($this->chatDetails) :'',
            'client'=>(!empty($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                            ]:'',
            'user'=>(!empty($this->user))?
                            [
                                'id'=>$this->user->id,
                                'user_name'=>$this->user->user_name,
                                'profile_image'=>$this->user->profile_image,
                                'address'=>(!empty($this->user->userDetails)) ? $this->user->userDetails->address : '',
                                'eduction_details'=>(!empty($this->user->user_eduction_details)) ? $this->user->user_eduction_details : ''
                            ]:'',
        ];
    }

    public function getMedicineNames($chatDetails){
        $data = [];
        if(!empty($chatDetails)){
            foreach ($chatDetails as $key => $value) {
                $data[]=[
                        'medicine_name'=> (isset($value->medicine_name)) ? $value->medicine_name : '',
                        'description'=> (isset($value->quanity)) ? $value->patient_direction : '',
                        'quantity'=> (isset($value->quanity)) ? $value->quanity : '',
                        'price'=> (isset($value->price)) ? $value->price : ''
                ];
            }
        }
        return $data;
    }
}
