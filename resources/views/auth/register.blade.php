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
                                            <div id="profileImageWrapper" class="row mb-3">
                                                <div class="col-sm-7 col-9 mx-auto position-relative">
                                                    <p class="mb-1 text-center">Profil</p>

                                                    <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" class="other-user-image img-fluid img-thumbnail rounded-4">
                                                    <label role="button" for="image_profile" class="btn btn-secondary rounded-circle position-absolute end-0 bottom-0">
                                                        <i class="bi bi-pencil-fill text-white fs-5"></i>
                                                        <input type="file" name="image_profile" id="image_profile" class="d-none">
                                                    </label>
                                                    <input type="hidden" name="image_64" id="image_64">
                                                </div>
                                            </div>

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

                                            <div class="mb-0">
                                                <label for="birthdate" class="form-label">Date de naissance</label>
                                                <input type="text" name="birthdate" class="form-control" id="birthdate">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5 col-sm-6 me-auto">
                                        <div class="card card-body p-4">
                                            <div class="mb-3">
                                                <label for="address_1" class="form-label">Adresse 1</label>
                                                <textarea class="form-control" name="address_1" id="address_1"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="address_2" class="form-label">Adresse 2 (Facultatif)</label>
                                                <textarea class="form-control" name="address_2" id="address_2"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="p_o_box" class="form-label">Boîte postale</label>
                                                <input type="text" name="p_o_box" class="form-control" id="p_o_box">
                                            </div>

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
                                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Enregistrer</button>
                                        <a href="{{ route('login') }}" class="btn btn-secondary w-100 mt-2 rounded-pill text-white">Annuler</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

@endsection
