<?php

namespace App\Jobs;

use App\Models\CustomerOrder;
use App\Models\Panel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CheckExpiredPanelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Vérifie les commandes expirées
        $expiredOrders = CustomerOrder::where('end_date', '<', Carbon::now())
                                        ->where('is_paid', 1)
                                        ->get();

        foreach ($expiredOrders as $order) {
            $panel = Panel::find($order->panel_id);
            if ($panel && $panel->is_available == 0) {
                $panel->update(['is_available' => 1]);
                Log::info("Le panneau ID {$panel->id} est maintenant disponible.");
            }
        }
    }
}
