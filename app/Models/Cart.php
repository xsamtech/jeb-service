<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * MANY-TO-MANY
     * Several panels for several carts
     */
    public function panels(): BelongsToMany
    {
        return $this->belongsToMany(Panel::class)->withTimestamps()->withPivot(['quantity', 'is_valid']);
    }

    /**
     * ONE-TO-MANY
     * One user for several carts
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Total price of panels
     *
     * @return float
     */
    public function totalPanelsPrices(): float
    {
        $total = 0;

        foreach ($this->panels as $panel) {
            $quantity = $panel->pivot->quantity ?? 1;
            $price = $panel->unit_price * $quantity;

            $total += $price;
        }

        return round($total, 2);
    }
}
