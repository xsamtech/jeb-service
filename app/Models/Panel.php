<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Panel extends Model
{
    use HasFactory;

    protected $table = 'panels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * MANY-TO-ONE
     * Several customer_orders for a panel
     */
    public function customer_orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }

    /**
     * MANY-TO-ONE
     * Several files for a panel
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
