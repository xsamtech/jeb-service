<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CustomerOrder extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price_at_that_time' => $this->price_at_that_time,
            'end_date' => $this->end_date,
            'expenses' => Expense::collection($this->expenses),
            'face' => Face::make($this->face),
            'user' => User::make($this->user),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_at_explicit' => explicitDate($this->created_at->format('Y-m-d H:i:s')),
            'updated_at_explicit' => explicitDate($this->updated_at->format('Y-m-d H:i:s')),
            'user_id' => $this->user_id,
            'cart_id' => $this->cart_id
        ];
    }
}
