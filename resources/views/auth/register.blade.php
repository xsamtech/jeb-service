@extends('layouts.auth', ['page_title' => 'S\'inscrire'])

@section('auth-content')

            <!-- Title Section-->
            <section class="pb-1">
                <div class="container px-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">S'inscrire</span></h1>
                    </div>
                </div>
            </section>

            <!-- Login Section-->
            <section class="pb-3">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-12">
                            <form method="POST" action="{{ route('register') }}">
    @csrf
                                <div class="row g-3">
                                    <div class="col-lg-5 col-sm-6 ms-auto">
                                        <div class="card card-body p-4">
                                            <div class="mb-3">
                                                <label for="firstname" class="form-label">Prénom</label>
                                                <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" id="firstname" value="{{ old('firstname') }}" autofocus>
    @error('firstname')
                                                <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="lastname" class="form-label">Nom</label>
                                                <input type="text" name="lastname" class="form-control" id="lastname">
                                            </div>

                                            <div class="mb-3">
                                                <label for="surname" class="form-label">Post-nom</label>
                                                <input type="text" name="surname" class="form-control" id="surname">
                                            </div>

                                            <div class="mb-3 text-center">
                                                <label class="form-label fw-bold">Sexe</label>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="male" value="M">
                                                        <label class="form-check-label" for="male">Homme</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="female" value="F">
                                                        <label class="form-check-label" for="female">Femme</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5 col-sm-6 me-auto">
                                        <div class="card card-body p-4">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">E-mail</label>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}">
    @error('email')
                                                <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">N° de téléphone</label>
                                                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Mot de passe</label>
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
    @error('password')
                                                <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation">
    @error('password_confirmation')
                                                <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
    @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-4 col-sm-6 mx-auto">
                                        <button type="submit" class="btn btn-success w-100 rounded-pill">Inscription</button>
                                        <a href="{{ route('login') }}" class="btn btn-secondary w-100 mt-2 rounded-pill text-white">Se connecter</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

@endsection
