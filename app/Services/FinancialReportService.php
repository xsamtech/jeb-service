<?php

namespace App\Services;

use App\Models\Accountancy;
use App\Models\Cart;
use App\Models\CustomerOrder;
use App\Models\Expense;
use Carbon\Carbon;

/**
 * Statistics according to a specific period
 * 
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class FinancialReportService
{
    public function getFinancialReport(string $periodType = 'monthly'): array
    {
        $now = Carbon::now();

        // 1. Define the period limits
        switch ($periodType) {
            case 'daily':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'weekly':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'monthly':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'quarterly':
                $start = $now->copy()->firstOfQuarter();
                $end = $now->copy()->lastOfQuarter();
                break;
            case 'half-yearly':
                $start = $now->month <= 6 ? $now->copy()->startOfYear() : $now->copy()->startOfYear()->addMonths(6);
                $end = $start->copy()->addMonths(6)->subDay(); // Fin du semestre
                break;
            case 'yearly':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                throw new \InvalidArgumentException('Période invalide.');
        }

        // 2. Total earnings: orders linked to paid baskets
        $carts = Cart::whereBetween('created_at', [$start, $end])->get();
        $totalEarnings = $carts->sum(function ($cart) {
            return $cart->remaining_amount; // Utilise la méthode getRemainingAmountAttribute()
        });

        // 3. Total expenses
        $totalExpenses = Expense::whereBetween('outflow_date', [$start, $end])
            ->sum('amount');

        // 4. In the box (via accountancies)
        // 4.1 Inflows (entries via paid carts)
        $cartInflow = Accountancy::whereHas('cart', function ($query) use ($start, $end) {
            $query->where('is_paid', 1)
                ->whereBetween('created_at', [$start, $end]);
        })
            ->with('cart.customer_orders')
            ->get()
            ->flatMap(function ($accountancy) {
                return $accountancy->cart->customer_orders;
            })
            ->sum('price_at_that_time');

        // 4.2 Outflows (outflows via expenses)
        $expenseOutflow = Accountancy::whereHas('expense', function ($query) use ($start, $end) {
            $query->whereBetween('outflow_date', [$start, $end]);
        })
            ->with('expense')
            ->get()
            ->sum(fn($a) => $a->expense->amount);

        $inTheBox = $cartInflow - $expenseOutflow;

        // 5. Total tithes
        $totalTithes = $carts->sum(function ($cart) {
            return $cart->dime10PercentExpensesTotal; // Utilise la méthode getRemainingAmountAttribute()
        });

        return [
            'period' => ucfirst($periodType),
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_earnings' => formatDecimalNumber($totalEarnings),
            'total_expenses' => formatDecimalNumber($totalExpenses),
            'total_tithes' => formatDecimalNumber($totalTithes),
            'in_the_box' => formatDecimalNumber($inTheBox),
        ];
    }
}
