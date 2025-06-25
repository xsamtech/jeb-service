<?php

namespace App\Console\Commands;

use App\Models\CustomerOrder;
use App\Models\Panel;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CheckExpiredPanels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panels:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les panneaux expirés et les rend disponibles à nouveau';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Sélectionne les commandes expirées
        $expiredOrders = CustomerOrder::where('end_date', '<', Carbon::now())
                                    // Join la table carts pour vérifier le statut de "is_paid"
                                    ->join('carts', 'customer_orders.cart_id', '=', 'carts.id')
                                    ->where('carts.is_paid', 1) // Utilisation de is_paid dans la table carts
                                    ->get();

        // Pour chaque commande expirée, rendre le panneau disponible
        foreach ($expiredOrders as $order) {
            $panel = Panel::find($order->panel_id);

            if ($panel && $panel->is_available == 0) {
                $panel->update(['is_available' => 1]);
                $this->info("Le panneau ID {$panel->id} est maintenant disponible.");
            }
        }
    }
}
