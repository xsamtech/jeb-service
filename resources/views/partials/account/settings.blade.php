
                            <div class="card card-body border pt-sm-4 pt-0 px-4">
                                <div class="mt-sm-0 my-4 text-center">
                                    <h1 class="card-title fw-bolder"><span class="text-gradient d-inline">Paramètres du compte</span></h1>
                                </div>

                                <form method="POST" action="{{ route('dashboard.account.settings') }}">
@csrf
                                    <div class="row g-3">
                                        <div class="col-lg-4 col-sm-6">
                                            <label for="firstname" class="form-label fw-bold">Prénom</label>
                                            <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" id="firstname" value="{{ Auth::user()->firstname }}" autofocus>
@error('firstname')
                                            <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="lastname" class="form-label fw-bold">Nom</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname" value="{{ Auth::user()->lastname }}">
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="surname" class="form-label fw-bold">Post-nom</label>
                                            <input type="text" name="surname" class="form-control" id="surname" value="{{ Auth::user()->surname }}">
                                        </div>

                                        <div class="col-lg-4 col-sm-6 text-sm-start text-center">
                                            <label class="form-label fw-bold">Sexe</label>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="male" value="M"{{ Auth::user()->gender == 'M' ? ' checked' : '' }}>
                                                    <label class="form-check-label" for="male">Homme</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender" id="female" value="F"{{ Auth::user()->gender == 'F' ? ' checked' : '' }}>
                                                    <label class="form-check-label" for="female">Femme</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="birthdate" class="form-label fw-bold">Date de naissance</label>
                                            <input type="text" name="birthdate" class="form-control" id="birthdate" value="{{ !empty(Auth::user()->birthdate) ? explode('-', Auth::user()->birthdate)[2] . '/' . explode('-', Auth::user()->birthdate)[1] . '/' . explode('-', Auth::user()->birthdate)[0] : '' }}">
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="p_o_box" class="form-label fw-bold">Boîte postale</label>
                                            <input type="text" name="p_o_box" class="form-control" id="p_o_box" value="{{ Auth::user()->p_o_box }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="address_1" class="form-label fw-bold">Adresse 1</label>
                                            <textarea class="form-control" name="address_1" id="address_1">{{ Auth::user()->address_1 }}</textarea>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="address_2" class="form-label fw-bold">Adresse 2 (Facultatif)</label>
                                            <textarea class="form-control" name="address_2" id="address_2">{{ Auth::user()->address_2 }}</textarea>
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="email" class="form-label fw-bold">E-mail</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ Auth::user()->email }}">
@error('email')
                                            <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="phone" class="form-label fw-bold">N° de téléphone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ Auth::user()->phone }}">
                                        </div>

                                        <div class="col-lg-4 col-sm-6">
                                            <label for="username" class="form-label fw-bold">Nom d’utilisateur</label>
                                            <input type="text" name="username" class="form-control" id="username" value="{{ Auth::user()->username }}">
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-1">
                                        <div class="col-sm-6">
                                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
@error('password')
                                            <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="password_confirmation" class="form-label fw-bold">Confirmer mot de passe</label>
                                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation">
@error('password_confirmation')
                                            <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-sm-8 mx-auto">
                                            <button type="submit" class="btn bg-gradient-primary-to-secondary w-100 mt-4 rounded-pill text-white">Enregistrer les modifications</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
