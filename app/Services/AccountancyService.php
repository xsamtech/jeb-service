<?php

namespace App\Services;

use App\Models\Accountancy;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Statistics according to a specific period
 * 
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AccountancyService
{
    public function getBalanceSummary($groupBy = 'month', $startDate = null, $endDate = null, $perPage = 10, $page = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::minValue();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::maxValue();

        // Actifs
        $assetAccountancies = Accountancy::with(['cart.panels'])
            ->whereNotNull('cart_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($accountancy) use ($groupBy) {
                $date = Carbon::parse($accountancy->created_at);
                $period = match ($groupBy) {
                    'week' => $date->format('o-\WW'),
                    'year' => $date->format('Y'),
                    default => $date->format('Y-m'),
                };

                $total = $accountancy->cart->panels->sum(function ($panel) {
                    return ($panel->pivot->is_valid ?? 1)
                        ? ($panel->pivot->quantity ?? 1) * ($panel->unit_price ?? 0)
                        : 0;
                });

                $total_panels = $accountancy->cart->panels->sum(function ($panel) {
                    return ($panel->pivot->is_valid ?? 1)
                        ? $panel->pivot->quantity ?? 1
                        : 0;
                });

                return [
                    'period' => $period,
                    'total_assets' => $total,
                    'total_liabilities' => 0,
                    'total_panels' => $total_panels,
                    'balance' => $total,
                ];
            });

        // Passifs
        $liabilityAccountancies = Accountancy::with('expense')
            ->whereNotNull('expense_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($accountancy) use ($groupBy) {
                $date = Carbon::parse($accountancy->created_at);
                $period = match ($groupBy) {
                    'week' => $date->format('o-\WW'),
                    'year' => $date->format('Y'),
                    default => $date->format('Y-m'),
                };

                $total = $accountancy->expense->amount ?? 0;

                return [
                    'period' => $period,
                    'total_assets' => 0,
                    'total_liabilities' => $total,
                    'total_panels' => 0,
                    'balance' => -$total,
                ];
            });

        // Fusion et groupement
        $merged = $assetAccountancies->merge($liabilityAccountancies);

        $grouped = $merged
            ->groupBy('period')
            ->map(function (Collection $items, $period) {
                $assets = $items->sum('total_assets');
                $liabilities = $items->sum('total_liabilities');
                $panels = $items->sum('total_panels');

                return [
                    'period' => $period,
                    'total_assets' => $assets,
                    'total_liabilities' => $liabilities,
                    'total_panels' => $panels,
                    'balance' => $assets - $liabilities,
                ];
            })
            ->sortBy('period')
            ->values();

        // Pagination manuelle
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage();
        $paginated = new LengthAwarePaginator(
            $grouped->forPage($page, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginated;
    }
}
