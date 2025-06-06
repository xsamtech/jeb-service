<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
     * Several customer_orders for a user
     */
    public function customer_orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }

    /**
     * Unpaid orders
     */
    public function unpaidOrders()
    {
        return $this->hasMany(CustomerOrder::class)->whereHas('cart', fn($q) => $q->where('is_paid', 0));
    }

    /**
     * Unpaid cart
     */
    public function unpaidCart()
    {
        return $this->hasOneThrough(
            Cart::class,
            CustomerOrder::class,
            'user_id',   // Foreign Key on "CustomerOrder"
            'id',        // Foreign Key on "Cart"
            'id',        // Locale key on "User"
            'cart_id'    // Locale key on "CustomerOrder"
        )->where('is_paid', 0);
    }
}
