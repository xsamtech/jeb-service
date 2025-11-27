<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CustomerOrder;
use App\Models\Expense;
use App\Models\Face;
use App\Models\MonthData;
use App\Models\RentedFace;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTaxeImplantation(Request $request)
    {
        if (empty($request->amount)) {
            return back()->with('error_message', 'Veuillez mettre un montant pour cette dépense.');
        }

        // Récupérer le mois et l'année depuis le formulaire ou utiliser les valeurs par défaut
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        // Vérifier si cette taxe est déjà ajoutée
        $expense = Expense::where('panel_id', $request->panel_id)
                            ->where('designation', 'Taxe implantation')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->first();

        if ($expense) {
            $expense->update([
                'designation' => 'Taxe implantation',
                'amount' => $request->amount,
                'outflow_date' => now(),
                'updated_by' => auth()->id(),
            ]);

        } else {
            Expense::create([
                'designation' => 'Taxe implantation',
                'amount' => $request->amount,
                'panel_id' => $request->panel_id,
                'outflow_date' => now(),
                'created_by' => auth()->id(),
            ]);
        }

        return back()->with('success_message', 'Taxe d’implantation enregistrée avec succès.');
    }

    /**
     * POST: Record the display tax (and create the related logic)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTaxeAffichage(Request $request)
    {
        if (empty($request->amount)) {
            return back()->with('error_message', 'Veuillez mettre un montant pour cette dépense.');
        }

        // Récupérer le mois et l'année depuis le formulaire ou utiliser les valeurs par défaut
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        // Vérifier si cette taxe est déjà ajoutée
        $expense = Expense::where('rented_face_id', $request->rented_face_id)
                            ->where('designation', 'Taxe affichage')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->first();

        if ($expense) {
            $expense->update([
                'designation' => 'Taxe affichage',
                'amount' => $request->amount,
                'outflow_date' => now(),
                'updated_by' => auth()->id(),
            ]);

        } else {
            // Enregistrer la dépense "Taxe affichage"
            Expense::create([
                'designation' => 'Taxe affichage',
                'amount' => $request->amount,
                'outflow_date' => now(),
                'created_by' => auth()->id(),
                'rented_face_id' => $request->rented_face_id,
            ]);
        }

        return back()->with('success_message', 'Taxe d’affichage enregistrée avec succès.');
    }

    /**
     * POST: Record other expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOtherExpense(Request $request)
    {
        if (empty($request->designation)) {
            return back()->with('error_message', 'Veuillez donner une désignation pour cette dépense.');
        }

        if (empty($request->amount)) {
            return back()->with('error_message', 'Veuillez mettre un montant pour cette dépense.');
        }

        if (empty($request->rented_face_id) AND empty($request->month_data_id)) {
            return back()->with('error_message', 'Pour quel sujet, cette dépense ?');
        }

        Expense::create([
            'designation' => $request->designation,
            'amount' => $request->amount,
            'outflow_date' => now(),
            'created_by' => auth()->id(),
            'rented_face_id' => $request->rented_face_id,
            'month_data_id' => $request->month_data_id,
        ]);

        if (!empty($request->month_data_id)) {
            $month_data = MonthData::find($request->month_data_id);

            if (!$month_data) {
                return back()->with('error_message', 'Données du mois non trouvées');
            }
            // Récupérer le mois et l'année depuis le formulaire ou utiliser les valeurs par défaut
            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);
            // Nouveau montant total restant du mois
            $new_remaining_amount = $month_data->remaining_amount - $request->amount;

            $month_data->update([
                'month' => $month,
                'year' => $year,
                'remaining_amount' => $new_remaining_amount,
            ]);
        }

        return back()->with('success_message', 'Dépense enregistrée avec succès.');
    }

    /**
     * POST: Update the tithe payment status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTithePaid(Request $request)
    {
        $month_data = MonthData::find($request->month_data_id);

        if (!$month_data) {
            return back()->with('error_message', 'Données du mois non trouvées');
        }

        $month_data->update([
            'tithe_paid' => $request->tithe_paid,
        ]);

        return back()->with('success_message', 'Etat de paiement de la dîme mise à jour.');
    }

    /**
     * POST: Update the lease end date
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEndDate(Request $request)
    {
        // $validated = $request->validate([
        //     'customer_order_id' => 'required|exists:customer_orders,id',
        //     'end_date' => 'required|date|after:today',
        // ]);

        $rented_face = RentedFace::findOrFail($request->rented_face_id);
        $formattedDate = !empty($request->end_date) ? 
                            Carbon::createFromFormat('d/m/Y H:i', $request->end_date)->format('Y-m-d H:i:s') : now()->addDay();

        $rented_face->update(['end_date' => $formattedDate]);

        return back()->with('success_message', 'Date de fin mise à jour.');
    }
}
