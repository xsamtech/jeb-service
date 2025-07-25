<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Cart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'payment_code' => $this->payment_code,
            'is_paid' => $this->is_paid,
            'total_amount' => $this->total_amount . '$',
            'remaining_amount' => formatDecimalNumber($this->remainingAmount) . '$',
            'tithe_10_percent_expenses_total' => formatDecimalNumber($this->dime10PercentExpensesTotal) . '$',
            'other_expenses_total' => formatDecimalNumber($this->otherExpensesTotal) . '$',
            'all_expenses_total' => formatDecimalNumber($this->allExpensesTotal) . '$',
            'orders' => CustomerOrder::collection($this->customer_orders),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_at_explicit' => explicitDate($this->created_at),
            'updated_at_explicit' => explicitDate($this->updated_at)
        ];
    }
}
