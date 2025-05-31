<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'surname' => $this->surname,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'age' => !empty($this->birthdate) ? $this->age() : null,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'p_o_box' => $this->p_o_box,
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'password' => $this->password,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'remember_token' => $this->remember_token,
            'api_token' => $this->api_token,
            'is_active' => $this->is_active,
            'avatar_url' => $this->avatar_url != null ? $this->avatar_url : getWebURL() . '/assets/img/user.png',
            'roles' => Role::collection($this->roles)->sortByDesc('created_at')->toArray(),
            'unpaidPanels' => $this->unpaidPanels()->toArray(),
            'totalUnpaidPanels' => $this->totalUnpaidPanels(),
            'paidPanels' => $this->paidPanels()->toArray(),
            'totalPaidPanels' => $this->totalPaidPanels(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
