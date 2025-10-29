<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerOrder;
use App\Models\Expense;
use App\Models\Face;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class HomeController extends Controller
{
    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * GET: Services list page
     *
     * @return \Illuminate\View\View
     */
    public function services()
    {
        return view('services');
    }

    /**
     * GET: Order page
     *
     * @return \Illuminate\View\View
     */
    public function order()
    {
        return view('order');
    }

    /**
     * GET: Contact page
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('contact');
    }

    // ==================================== HTTP POST METHODS ====================================
    /**
     * POST: Register the establishment tax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function storeTaxeImplantation(Request $request)
    {
        // $validated = $request->validate([
        //     'panel_id' => 'required|exists:panels,id',
        //     'amount' => 'required|numeric|min:0',
        //     'month' => 'required|integer|min:1|max:12',
        //     'year' => 'required|integer|min:2020|max:' . now()->year,
        // ]);

        $exists = Expense::where('panel_id', $request->panel_id)
                            ->where('designation', 'Taxe implantation')
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month)
                            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Taxe d’implantation déjà enregistrée pour ce mois.');
        }

        Expense::create([
            'designation' => 'Taxe implantation',
            'amount' => $request->amount,
            'panel_id' => $request->panel_id,
            'outflow_date' => now(),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success_message', 'Taxe d’implantation enregistrée avec succès.');
    }

    /**
     * POST: Record the display tax (and create the related logic)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function storeTaxeAffichage(Request $request)
    {
        // $validated = $request->validate([
        //     'face_id' => 'required|exists:faces,id',
        //     'amount' => 'required|numeric|min:0',
        // ]);

        $face = Face::with('panel', 'customer_orders')->findOrFail($request->face_id);

        // Vérifie si une location est encore en cours
        $activeOrder = $face->customer_orders()
                            ->where('end_date', '>=', now())
                            ->latest('end_date')
                            ->first();

        if ($activeOrder) {
            return back()->with('error_message', 'Impossible d’ajouter : la location est toujours en cours jusqu’au ' . $activeOrder->end_date->format('d/m/Y'));
        }

        // Trouver ou créer un panier pour le mois courant
        $cart = Cart::firstOrCreate([
            'payment_code' => now()->format('Ym'), // exemple: 202510
        ], [
            'is_paid' => 0,
            'created_at' => now(),
        ]);

        // Créer la commande client
        $customerOrder = CustomerOrder::create([
            'face_id' => $face->id,
            'cart_id' => $cart->id,
            'price_at_that_time' => $face->panel->price,
            'end_date' => now()->addDay(),
            'user_id' => auth()->id(),
        ]);

        // Enregistrer la dépense "Taxe affichage"
        Expense::create([
            'designation' => 'Taxe affichage',
            'amount' => $request->amount,
            'customer_order_id' => $customerOrder->id,
            'panel_id' => $face->panel_id,
            'outflow_date' => now(),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success_message', 'Taxe d’affichage enregistrée avec succès.');
    }

    /**
     * POST: Record the display tax (and create the related logic)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function storeOtherExpense(Request $request)
    {
        // $validated = $request->validate([
        //     'face_id' => 'required|exists:faces,id',
        //     'amount' => 'required|numeric|min:0',
        // ]);

        $face = Face::with('panel', 'customer_orders')->findOrFail($request->face_id);

        // Créer la commande client
        $customerOrder = CustomerOrder::find($request->customer_order_id);

        if (!$customerOrder) {
            return back()->with('error_message', 'Location non trouvé');
        }

        // Enregistrer la dépense "Taxe affichage"
        Expense::create([
            'designation' => $request->designation,
            'amount' => $request->amount,
            'customer_order_id' => $customerOrder->id,
            'panel_id' => $face->panel_id,
            'outflow_date' => now(),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success_message', 'Dépense enregistrée avec succès.');
    }

    /**
     * POST: Update the lease end date
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function updateEndDate(Request $request)
    {
        // $validated = $request->validate([
        //     'customer_order_id' => 'required|exists:customer_orders,id',
        //     'end_date' => 'required|date|after:today',
        // ]);

        $order = CustomerOrder::findOrFail($request->customer_order_id);
        $formattedDate = !empty($request->end_date) ? 
                            Carbon::createFromFormat('d/m/Y H:i', $request->end_date)->format('Y-m-d H:i:s') : now()->addDay();

        $order->update(['end_date' => $formattedDate]);

        return back()->with('success_message', 'Date de fin mise à jour.');
    }
}
