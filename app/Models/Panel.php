<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * MANY-TO-MANY
     * Several carts for several panels
     */
    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)->withTimestamps()->withPivot(['quantity', 'is_valid']);
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
