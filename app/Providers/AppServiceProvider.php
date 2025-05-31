<?php

namespace App\Providers;

use App\Models\Panel;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\User as ResourcesUser;
use App\Http\Resources\Panel as ResourcesPanel;
use App\Models\Role;
use Illuminate\Support\Facades\App;
use App\Services\AccountancyService;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $request = request(); // plus propre
            $accountancyService = App::make(AccountancyService::class);

            $groupBy = $request->get('group_by', 'month');
            $start = $request->get('start_date');
            $end = $request->get('end_date');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $balanceSummary = $accountancyService->getBalanceSummary($groupBy, $start, $end, $perPage, $page);
            $weeklySummary = $accountancyService->getBalanceSummary('week');

            // Préparer pour ApexCharts
            $chartLabels = $weeklySummary->pluck('period');
            $chartAssets = $weeklySummary->pluck('total_assets');
            $chartLiabilities = $weeklySummary->pluck('total_liabilities');
            $chartPanels = $weeklySummary->pluck('total_panels');

            $view->with('users', ResourcesUser::collection(User::all()));
            $view->with('roles', ResourcesUser::collection(Role::all()));
            $view->with('panels', ResourcesPanel::collection(Panel::all()));
            $view->with('balance_summary', $balanceSummary);
            $view->with('chartLabels', $chartLabels);
            $view->with('chartAssets', $chartAssets);
            $view->with('chartLiabilities', $chartLiabilities);
            $view->with('chartPanels', $chartPanels);
        });
    }
}
