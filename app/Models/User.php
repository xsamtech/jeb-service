<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_connection' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Accessor for Age.
     */
    public function age(): int
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }

    /**
     * MANY-TO-MANY
     * Several roles for several users
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * MANY-TO-ONE
     * Several carts for a user
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * All unpaid panels
     */
    public function unpaidPanels()
    {
        $unpaid_cart = $this->carts()->where('is_paid', 0)->latest()->first();

        if (!$unpaid_cart) {
            return collect();
        }

        return $unpaid_cart->panels()->orderByPivot('created_at', 'desc')->get();
    }

    /**
     * All paid panels
     */
    public function paidPanels()
    {
        $paid_cart = $this->carts()->where('is_paid', 1)->latest()->first();

        if (!$paid_cart) {
            return collect();
        }

        return $paid_cart->panels()->orderByPivot('created_at', 'desc')->get();
    }

    /**
     * Total unpaid panels price
     */
    public function totalUnpaidPanels()
    {
        $unpaid_cart = $this->carts()->where('is_paid', 0)->latest()->first();

        if (!$unpaid_cart) {
            return 0;
        }

        return $unpaid_cart->totalPanelsPrices();
    }

    /**
     * Total paid panels price
     */
    public function totalPaidPanels()
    {
        $paid_cart = $this->carts()->where('is_paid', 1)->latest()->first();

        if (!$paid_cart) {
            return 0;
        }

        return $paid_cart->totalPanelsPrices();
    }
}
