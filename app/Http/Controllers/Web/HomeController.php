<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Face;
// use App\Models\Face;
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
        if (empty($request->amount) OR !is_numeric($request->amount) OR formatIntegerNumber($request->amount) == 0) {
            return back()->with('error_message', 'Veuillez mettre un montant valide et supérieur à zéro.');
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
                'amount' => $request->amount,
                'outflow_date' => !empty($request->outflow_date) ? Carbon::createFromFormat('d/m/Y H:i', $request->outflow_date)->format('Y-m-d H:i:s') : now(),
                'updated_by' => auth()->id(),
            ]);

        } else {
            Expense::create([
                'designation' => 'Taxe implantation',
                'amount' => $request->amount,
                'panel_id' => $request->panel_id,
                'outflow_date' => !empty($request->outflow_date) ? Carbon::createFromFormat('d/m/Y H:i', $request->outflow_date)->format('Y-m-d H:i:s') : now(),
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
        if (empty($request->amount) OR !is_numeric($request->amount) OR formatIntegerNumber($request->amount) == 0) {
            return back()->with('error_message', 'Veuillez mettre un montant valide et supérieur à zéro.');
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
                'amount' => $request->amount,
                'outflow_date' => !empty($request->outflow_date) ? Carbon::createFromFormat('d/m/Y H:i', $request->outflow_date)->format('Y-m-d H:i:s') : now(),
                'updated_by' => auth()->id(),
            ]);

        } else {
            // Récupérer la face pour le rendre indisponible
            $face = Face::find($request->rented_face_id);

            if (!$face) {
                return back()->with('error_message', 'Face non trouvée');
            }

            $face->update(['is_available' => 0]);

            // Enregistrer la location de la face
            $rented_face = RentedFace::create([
                'price' => 0,
                'end_date' => Carbon::now()->addDay(),
                'created_by' => auth()->id(),
                'face_id' => $face->id,
            ]);

            // Enregistrer la dépense "Taxe affichage"
            Expense::create([
                'designation' => 'Taxe affichage',
                'amount' => $request->amount,
                'outflow_date' => !empty($request->outflow_date) ? Carbon::createFromFormat('d/m/Y H:i', $request->outflow_date)->format('Y-m-d H:i:s') : now(),
                'created_by' => auth()->id(),
                'rented_face_id' => $rented_face->id,
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
        $month_data = null;

        if (empty($request->designation)) {
            return back()->with('error_message', 'Veuillez donner une désignation pour cette dépense.');
        }

        if (empty($request->amount) OR !is_numeric($request->amount) OR formatIntegerNumber($request->amount) == 0) {
            return back()->with('error_message', 'Veuillez mettre un montant valide et supérieur à zéro.');
        }

        if (empty($request->panel_id) AND empty($request->rented_face_id) AND empty($request->month_data_id)) {
            return back()->with('error_message', 'Pour quel sujet, cette dépense ?');
        }

        if (!empty($request->month_data_id)) {
            // Récupérer le mois et l'année depuis le formulaire ou utiliser les valeurs par défaut
            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);
            $month_data = MonthData::where('id', $request->month_data_id)->where('month', $month)->where('year', $year)->first();

            if ($month_data) {
                // Nouveau montant total restant du mois
                $new_remaining_amount = $month_data->remaining_amount - $request->amount;

                $month_data->update([
                    'remaining_amount' => $new_remaining_amount,
                ]);

            } else {
                MonthData::create([
                    'month' => $month,
                    'year' => $year,
                    'remaining_amount' => $request->remaining_amount,
                ]);
            }
        }

        Expense::create([
            'designation' => $request->designation,
            'amount' => $request->amount,
            'outflow_date' => !empty($request->outflow_date) ? Carbon::createFromFormat('d/m/Y H:i', $request->outflow_date)->format('Y-m-d H:i:s') : now(),
            'created_by' => auth()->id(),
            'panel_id' => $request->panel_id,
            'rented_face_id' => $request->rented_face_id,
            'month_data_id' => $request->month_data_id,
        ]);

        return back()->with('success_message', 'Dépense enregistrée avec succès.');
    }

    /**
     * POST: Update the renting price
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRentedPrice(Request $request)
    {
        if (empty($request->rented_face_id)) {
            return back()->with('error_message', 'Veuillez choisir une face louée.');
        }

        if (empty($request->price)) {
            return back()->with('error_message', 'Veuillez donner le prix de la location.');
        }

        $rented_face = RentedFace::find($request->rented_face_id);

        if (!$rented_face) {
            return back()->with('error_message', 'Face louée non trouvée.');
        }

        $rented_face->update(['price' => $request->price]);

        return back()->with('success_message', 'Prix de la location mis à jour.');
    }

    /**
     * POST: Update the renting end date
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEndDate(Request $request)
    {
        if (empty($request->rented_face_id)) {
            return back()->with('error_message', 'Veuillez choisir une face louée.');
        }

        if (empty($request->end_date)) {
            return back()->with('error_message', 'Veuillez donner la date de fin de location.');
        }

        $rented_face = RentedFace::find($request->rented_face_id);

        if (!$rented_face) {
            return back()->with('error_message', 'Face louée non trouvée.');
        }

        $formattedDate = !empty($request->end_date) ? 
                            Carbon::createFromFormat('d/m/Y H:i', $request->end_date)->format('Y-m-d H:i:s') : now()->addDay();

        $rented_face->update(['end_date' => $formattedDate]);

        return back()->with('success_message', 'Date de fin mise à jour.');
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
}
