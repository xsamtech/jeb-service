<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * One face for several customer_orders
     */
    public function face(): BelongsTo
    {
        return $this->belongsTo(Face::class);
    }

    /**
     * ONE-TO-MANY
     * One user for several customer_orders
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * MANY-TO-ONE
     * Several expenses for a panel
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Order tithe
     */
    public function getTitheAttribute()
    {
        return $this->expenses()->where('designation', 'Dîme (10%)')->pluck('amount')->first();
    }

    /**
     * Undirect relationship between "customer_orders" and "panels"
     */
    public function panel()
    {
        return $this->hasOneThrough(Panel::class, Face::class, 'panel_id', 'id', 'face_id', 'panel_id');
    }
}
