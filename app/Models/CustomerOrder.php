<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CustomerOrder extends Model
{
    use HasFactory;

    protected $table = 'customer_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One cart for several customer_orders
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * ONE-TO-MANY
     * One panel for several customer_orders
     */
    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    /**
     * ONE-TO-MANY
     * One user for several customer_orders
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
