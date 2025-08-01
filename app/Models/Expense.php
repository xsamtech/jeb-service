<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One customer_order for several files
     */
    public function customer_order(): BelongsTo
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    /**
     * MANY-TO-ONE
     * Several accountancies for an expense
     */
    public function accountancies(): HasMany
    {
        return $this->hasMany(Accountancy::class);
    }

    /**
     * Expenses of the precise period
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function titheExpenses(): Collection
    {
        return self::where('designation', 'Dîme (10%)')->get();
    }

    /**
     * Expenses of the precise period
     * 
     * @param  int $month
     * @param  int $year
     * @return float
     */
    public static function totalMonthlyExpenses($month, $year): float
    {
        return self::whereMonth('outflow_date', $month)->whereYear('outflow_date', $year)->sum('amount');
    }

    /**
     * Expenses of the precise period
     * 
     * @param  int $month
     * @param  int $year
     * @return float
     */
    public static function totalMonthlyTitheExpenses($month, $year): float
    {
        return self::where('designation', 'Dîme (10%)')->whereMonth('outflow_date', $month)->whereYear('outflow_date', $year)->sum('amount');
    }
}
