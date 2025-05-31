<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
     * @return float
     */
    public static function totalMonthlyExpenses($month, $year): float
    {
        return self::whereMonth('outflow_date', $month)->whereYear('outflow_date', $year)->sum('amount');
    }
}
