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


    /**
     * Money left in cart (TOTAL ORDERS - EXPENSES)
     */
    public function getRemainingAmountAttribute()
    {
        // Total orders for this cart
        $totalOrders = $this->customer_orders()->sum('price_at_that_time');  // The total price of orders

        // Total expenses associated with each order
        $totalTitheExpenses = $this->customer_orders()
                                ->whereHas('expenses')
                                ->with('expenses')  // Relationship with expenses
                                ->get()
                                ->flatMap(function ($order) {
                                    // Calculating the total expenses for each order
                                    return $order->expenses->pluck('amount');
                                    // return $order->expenses->where('designation', 'Dîme (10%)')->pluck('amount');
                                })->sum();

        // The remaining money is the difference between the total orders and expenses
        return $totalOrders - $totalTitheExpenses;
    }

    /**
     * Total expenses "Dîme (10%)"
     */
    public function getDime10PercentExpensesTotalAttribute()
    {
        return $this->customer_orders()
                ->whereHas('expenses', function ($query) {
                    $query->where('designation', 'Dîme (10%)');  // Filtrer uniquement les dépenses "Dîme (10%)"
                })
                ->with(['expenses' => function ($query) {
                    $query->where('designation', 'Dîme (10%)');  // Charger uniquement les dépenses "Dîme (10%)"
                }])
                ->get()
                ->flatMap(function ($order) {
                    // Calculer la somme des montants des dépenses "Dîme (10%)" pour chaque commande
                    return $order->expenses->pluck('amount');
                })
                ->sum();
    }

    /**
     * Total expenses other than "Dîme (10%)"
     */
    public function getOtherExpensesTotalAttribute()
    {
        return $this->customer_orders()
                ->whereHas('expenses', function ($query) {
                    $query->where('designation', '<>', 'Dîme (10%)');  // Filtrer les dépenses différentes de "Dîme (10%)"
                })
                ->with(['expenses' => function ($query) {
                    $query->where('designation', '<>', 'Dîme (10%)');  // Charger les dépenses différentes de "Dîme (10%)"
                }])
                ->get()
                ->flatMap(function ($order) {
                    // Calculer la somme des montants des dépenses "Dîme (10%)" pour chaque commande
                    return $order->expenses->pluck('amount');
                })
                ->sum();
    }
}
