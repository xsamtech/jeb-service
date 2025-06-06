<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * MANY-TO-ONE
     * Several customer_orders for a cart
     */
    public function customer_orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }

    /**
     * MANY-TO-ONE
     * Several accountancies for a cart
     */
    public function accountancies(): HasMany
    {
        return $this->hasMany(Accountancy::class);
    }

    /**
     * Total price of ordered panels
     *
     * @return float
     */
    public function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->customerOrders->sum('price_at_that_time')
        );
    }
}
