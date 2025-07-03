<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CustomerOrder;
use App\Models\Panel;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Check admins
        $admins_exist = User::whereHas('roles', fn($q) => $q->where('roles.role_name', 'Administrateur'))->exists();

        return view('auth.login', ['admins_exist' => $admins_exist]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$login_type => $request->login, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => __('Identifiants incorrects.'),
            ]);
        }

        $expiredOrders = CustomerOrder::where('end_date', '<', Carbon::now())
                                    // Join la table carts pour vérifier le statut de "is_paid"
                                    ->join('carts', 'customer_orders.cart_id', '=', 'carts.id')
                                    ->where('carts.is_paid', 1) // Utilisation de is_paid dans la table carts
                                    ->get();

        foreach ($expiredOrders as $order) {
            $panel = Panel::find($order->panel_id);

            if ($panel && $panel->is_available == 0) {
                $panel->update(['is_available' => 1]);

                Log::info("Le panneau ID {$panel->id} est maintenant disponible.");
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
