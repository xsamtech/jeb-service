@if (Route::is('dashboard.account.settings'))
        <!-- ### Crop user image ### -->
        <div class="modal fade" id="cropModalUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0 border-bottom-0">
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body pb-3">
                        <h5 class="text-center text-muted">{{ __('miscellaneous.crop_before_save') }}</h5>

                        <div class="container">
                            <div class="row">
                                <div class="col-12 mb-sm-0 mb-4">
                                    <div class="bg-image">
                                        <img src="" id="retrieved_image" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                        <button type="button" id="crop_avatar" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">{{ __('miscellaneous.register') }}</button>
                    </div>
                </div>
            </div>
        </div>
@endif

@if (Route::is('dashboard.users'))
        <!-- ### Add new admin ### -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#userModal">Ajouter un administrateur</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax-loader" class="position-absolute d-none" style="top: 10px; right: 10px;">
                            <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="32" height="32">
                        </div>
                        <form id="addUserForm" action="{{ route('dashboard.users') }}" method="POST">
    @csrf
                            <input type="hidden" name="role_id" value="{{ $admin->id }}">

                            <div class="row g-3">
                                <div class="col-lg-4 col-sm-5 mx-auto">
                                    <div id="profileImageWrapper" class="mb-3 position-relative">
                                        <p class="mb-1 text-center fw-bold">Profil</p>

                                        <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" class="other-user-image img-fluid img-thumbnail rounded-4">
                                        <label role="button" for="image_profile" class="btn btn-secondary rounded-circle position-absolute end-0 bottom-0">
                                            <i class="bi bi-pencil-fill text-white fs-5"></i>
                                            <input type="file" name="image_profile" id="image_profile" class="d-none">
                                        </label>
                                        <input type="hidden" name="image_64" id="image_64">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- First name -->
                                <div class="col-sm-6">
                                    <label for="firstname" class="form-label fw-bold">Prénom</label>
                                    <input type="text" name="firstname" class="form-control" id="firstname" autofocus>
                                </div>

                                <!-- Last name -->
                                <div class="col-sm-6">
                                    <label for="lastname" class="form-label fw-bold">Nom</label>
                                    <input type="text" name="lastname" class="form-control" id="lastname">
                                </div>

                                <!-- E-mail -->
                                <div class="col-sm-6">
                                    <label for="email" class="form-label fw-bold">E-mail</label>
                                    <input type="email" name="email" class="form-control" id="email">
{{-- @error('email')
                                    <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror --}}
                                </div>

                                <!-- Phone -->
                                <div class="col-sm-6">
                                    <label for="phone" class="form-label fw-bold">N° de téléphone</label>
                                    <input type="text" name="phone" class="form-control" id="phone">
                                </div>

                                <!-- Password -->
                                <div class="col-sm-6">
                                    <label for="password" class="form-label fw-bold">Mot de passe</label>
                                    <input type="password" name="password" class="form-control" id="password">
{{-- @error('password')
                                    <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror --}}
                                </div>

                                <!-- Confirm password -->
                                <div class="col-sm-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirmer mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
{{-- @error('password_confirmation')
                                    <small class="text-danger d-inline-block mt-1 float-end">{{ $message }}</small>
@enderror --}}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                                <button type="submit" class="btn btn-primary px-4 rounded-pill">{{ __('miscellaneous.register') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ### Crop other user image ### -->
        <div class="modal fade" id="cropModal_profile" tabindex="-1" aria-hidden="true" data-bs-backdrop="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0 border-bottom-0">
                        <button type="button" class="btn-close mt-1" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center text-muted">Recadrer l'image avant de l'enregistrer</h5>

                        <div class="container">
                            <div class="row">
                                <div class="col-12 mb-sm-0 mb-4">
                                    <div class="bg-image">
                                        <img src="" id="retrieved_image_profile" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" id="crop_profile" class="btn btn-primary px-4 rounded-pill" data-bs-dismiss="modal">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
@endif
