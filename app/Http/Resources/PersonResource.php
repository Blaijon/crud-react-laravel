<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
         'id'            => $this->id,
         'first_name'    => $this->first_name,
         'last_name'     => $this->last_name,
         'email_address' => $this->email_address,
         'status'        => $this->status,
         'created_at'    => $this->created_at,
         'updated_at'    => $this->updated_at,
     ];
 }
}
