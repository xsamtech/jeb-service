<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Face;
use App\Models\RentedFace;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Déterminer le type de connexion (email, téléphone, ou nom d'utilisateur)
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $login_type = 'email';
        } elseif (preg_match('/^\+?[0-9]{10,15}$/', $request->login)) {
            // Vérifier si c'est un numéro de téléphone valide (exemple : format international)
            $login_type = 'phone';
        } else {
            // Si ce n'est ni un email ni un téléphone, on considère que c'est un nom d'utilisateur
            $login_type = 'username';
        }

        // Vérification si les identifiants sont corrects
        $user = User::where($login_type, $request->login)->first();

        // Si l'utilisateur existe, on vérifie le mot de passe
        if ($user && !Hash::check($request->password, $user->password)) {
            // Mot de passe incorrect
            throw ValidationException::withMessages([
                'password' => __('Le mot de passe est incorrect.'),
            ]);
        }

        // Authentification réussie
        if (!Auth::attempt([$login_type => $request->login, 'password' => $request->password], $request->boolean('remember'))) {
            // Erreur générique pour le login
            throw ValidationException::withMessages([
                'login' => __('Identifiants incorrects.'),
            ]);
        }

        // Rendre un panneau disponible à nouveau
        $expiredRentedFaces = RentedFace::where('end_date', '<=', Carbon::now())->get();

        foreach ($expiredRentedFaces as $rentedFace) {
            $face = Face::find($rentedFace->face_id);

            if ($face && $face->is_available == 0) {
                $face->update(['is_available' => 1]);

                Log::info("La face du panneau « {$face->panel->location} » est maintenant disponible.");
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
