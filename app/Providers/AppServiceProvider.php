<?php

namespace App\Providers;

use App\Models\Panel;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\User as ResourcesUser;
use App\Http\Resources\Panel as ResourcesPanel;
use App\Models\CustomerOrder;
use App\Models\Expense;
use App\Services\FinancialReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;

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
        Carbon::setLocale(App::getLocale());
        Paginator::useBootstrap();

        view()->composer('*', function ($view) {
            $reportService = app(FinancialReportService::class);

            // 🕐 Période à afficher (peut aussi venir de la requête si besoin)
            $now = Carbon::now();
            $start = $now->copy()->startOfWeek();
            $end = $now->copy()->endOfWeek();

            // 📊 Préparer les données du graphique
            $period = CarbonPeriod::create($start, '1 day', $end);

            $labels = [];
            $earnings = [];
            $expenses = [];
            $panels = [];

            foreach ($period as $date) {
                $day = $date->copy();
                // $labels[] = $day->format('d/m');
                $labels[] = $day->isoFormat('ddd');

                $dayEarnings = CustomerOrder::whereHas('cart', fn($q) => $q->where('is_paid', 1))->whereDate('created_at', $day)->sum('price_at_that_time');
                $earnings[] = round($dayEarnings, 2);

                $dayExpenses = Expense::whereDate('outflow_date', $day)->sum('amount');
                $expenses[] = round($dayExpenses, 2);

                $dayPanels = CustomerOrder::whereDate('created_at', $day)->count();
                $panels[] = $dayPanels;
            }

            // 📈 Résumé du mois (gains, dépenses, TVA, etc.)
            $balance_summary = $reportService->getFinancialReport('weekly');

            // 🔁 Injection dans la vue
            $view->with('all_users', ResourcesUser::collection(User::all()));
            $view->with('all_panels', ResourcesPanel::collection(Panel::all()));
            $view->with('chartLabels', $labels);
            $view->with('chartEarnings', $earnings);
            $view->with('chartExpenses', $expenses);
            $view->with('chartPanels', $panels);
            $view->with('balance_summary', $balance_summary);
            $view->with('currentSummary', true);
        });
    }
}
