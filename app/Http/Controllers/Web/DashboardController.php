<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class DashboardController extends Controller
{
    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $total_orders = 1245999.73;
        $orders_paid = 57709.95;
        $tithe = $orders_paid / 10;
        $tva = ($orders_paid * 16) / 100;
        $rest_of_money = $orders_paid - ($tithe + $tva);

        return view('dashboard', [
            'total_orders' => formatIntegerNumber($total_orders),
            'orders_paid' => formatIntegerNumber($orders_paid),
            'tithe' => formatIntegerNumber($tithe),
            'tva' => formatIntegerNumber($tva),
            'rest_of_money' => formatIntegerNumber($rest_of_money),
        ]);
    }

    /**
     * GET: Customers list page
     *
     * @return \Illuminate\View\View
     */
    public function customers()
    {
        return view('customers');
    }

    /**
     * GET: Show customer page
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function customerDatas($id)
    {
        return view('customers');
    }

    /**
     * GET: Statistics page
     *
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        return view('statistics');
    }

    /**
     * GET: Messages page
     *
     * @return \Illuminate\View\View
     */
    public function messages()
    {
        return view('messages');
    }

    /**
     * GET: account page
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('account');
    }

    // ==================================== HTTP DELETE METHODS ====================================
    /**
     * GET: Delete customer
     *
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeCustomer($id)
    {
        // 
    }

    // ==================================== HTTP POST METHODS ====================================
    /**
     * POST: Update account
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Préparation des règles dynamiques
        $rules = [];

        if ($request->has('firstname')) {
            $rules['firstname'] = ['required', 'string', 'max:255'];
        }

        if ($request->has('lastname')) {
            $rules['lastname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('surname')) {
            $rules['surname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('gender')) {
            $rules['gender'] = ['nullable', Rule::in(['M', 'F'])];
        }

        if ($request->has('birthdate')) {
            $rules['birthdate'] = ['nullable', 'date_format:d/m/Y'];
        }

        if ($request->has('p_o_box')) {
            $rules['p_o_box'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('address_1')) {
            $rules['address_1'] = ['nullable', 'string'];
        }

        if ($request->has('address_2')) {
            $rules['address_2'] = ['nullable', 'string'];
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->has('username') && $request->input('username') !== $user->username) {
            $rules['username'] = ['required', 'string', 'max:45', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        if ($request->has('image_64')) {
            $rules['image_64'] = ['required', 'string', 'starts_with:data:image/'];
        }

        // Validation des champs présents uniquement
        $validated = $request->validate($rules);

        // Formatage de la date
        if (isset($validated['birthdate'])) {
            $validated['birthdate'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Hash mot de passe si présent
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Traitement de l'image base64 si présente
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_url = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_url, base64_decode($image));
            $validated['avatar_url'] = $image_url;

            unset($validated['image_64']);
        }

        // Mise à jour de l'utilisateur avec les champs valides
        $user->update($validated);

        // Mise à jour du PasswordReset uniquement si nécessaire
        $password_reset = !empty($user->email)
            ? \App\Models\PasswordReset::where('email', $user->email)->first()
            : \App\Models\PasswordReset::where('phone', $user->phone)->first();

        if ($password_reset) {
            $updateData = [];

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
            }

            $updateData['token'] = (string) random_int(1000000, 9999999);

            $password_reset->update($updateData);
        }

        // Retour conditionné : AJAX ou POST HTML
        return $request->expectsJson()
            ? response()->json(['success_message' => true, 'avatar_url' => $user->avatar_url ?? null])
            : back()->with('success_message', 'Vos informations ont bien été mises à jour.');
    }
}
