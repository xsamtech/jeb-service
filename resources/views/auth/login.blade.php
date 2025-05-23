@extends('layouts.auth', ['page_title' => 'Se connecter'])

@section('auth-content')

            <!-- Title Section-->
            <section class="pb-1">
                <div class="container px-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Se connecter</span></h1>
                    </div>
                </div>
            </section>

            <!-- Login Section-->
            <section class="pb-3">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-lg-5 col-sm-7 mx-auto">
                            <div class="card card-body p-sm-4">
                                <form method="POST" action="{{ route('login') }}">
    @csrf
                                    <div class="mb-3">
                                        <label for="login" class="form-label">Email ou n° de téléphone</label>
                                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" id="login" value="{{ old('login') }}" required autofocus>
    @error('login')
                                        <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
    @error('password')
                                        <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                    </div>

                                    <div class="mb-3 form-check d-flex justify-content-center">
                                        <input type="checkbox" name="remember" class="form-check-input me-2" id="remember">
                                        <label role="button" class="form-check-label" for="remember">Rester connecté</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Connexion</button>
    @if (!$admins_exist)
                                    <a href="{{ route('register') }}" class="btn btn-secondary w-100 mt-2 rounded-pill text-white">Créer un compte</a>
    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
