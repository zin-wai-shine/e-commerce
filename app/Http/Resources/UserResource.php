<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isVerified = $this->email_verified_at ? true : false;

        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'banned' => $this->banned,
            'role'  => $this->role,
            'verified' => $isVerified,
            'date'  => $this->created_at->format('d/M/Y'),
            'time'  => $this->created_at->format('h:i:s A')
        ];
    }
}
