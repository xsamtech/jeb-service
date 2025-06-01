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
                                </div>

                                <!-- Confirm password -->
                                <div class="col-sm-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirmer mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
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

@if (Route::is('dashboard.users.entity'))
        <!-- ### Add new admin ### -->
        <div class="modal fade" id="userEntityModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog{{ $entity == 'orders' ? ' modal-lg' : '' }}" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#userEntityModal">{{ $entity == 'orders' ? 'Ajouter une commande' : 'Ajouter un rôle' }}</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax-loader" class="position-absolute d-none" style="top: 10px; right: 10px;">
                            <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="32" height="32">
                        </div>
                        <form id="{{ $entity == 'orders' ? 'addOrderForm' : 'addRoleForm' }}" action="{{ route('dashboard.users.entity', ['entity' => $entity]) }}" method="POST">
        @csrf
                            <input type="hidden" name="customer_id" id="customer_id">
                            <div class="row g-2">
    @if ($entity == 'orders')
                                <!-- User data -->
                                <div class="col-lg-5">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="customersDataList" class="form-label fw-bold visually-hidden">Recherche client existant</label>
                                            <input class="form-control" list="datalistCustomerOptions" id="customersDataList" placeholder="Recherche client existant">
                                            <datalist id="datalistCustomerOptions">
        @forelse ($customers as $customer)
                                                    <option class="fs-6 p-2" value="{{ $customer->firstname . ' ' . $customer->lastname }}">
        @empty
        @endforelse
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="card card-body">
                                        <p class="card-text fw-bold">Identité du client</p>
                                        <!-- First name -->
                                        <div class="mb-2">
                                            <label for="firstname" class="form-label fw-bold visually-hidden">Prénom</label>
                                            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Prénom" autofocus>
                                        </div>

                                        <!-- Last name -->
                                        <div class="mb-2">
                                            <label for="lastname" class="form-label fw-bold visually-hidden">Nom</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Nom">
                                        </div>

                                        <!-- E-mail -->
                                        <div class="mb-2">
                                            <label for="email" class="form-label fw-bold visually-hidden">E-mail</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
                                        </div>

                                        <!-- Phone -->
                                        <div class="mb-2">
                                            <label for="phone" class="form-label fw-bold visually-hidden">N° de téléphone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="N° de téléphone">
                                        </div>
                                    </div>
                                </div>

                                <!-- Order data -->
                                <div class="col-lg-7">

                                </div>
    @else
                                <!-- Role name -->
                                <div class="col-12">
                                    <label for="role_name" class="form-label fw-bold">Nom du rôle</label>
                                    <input type="text" name="role_name" class="form-control" id="role_name" autofocus>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="role_description" class="form-label fw-bold">Description</label>
                                    <textarea class="form-control" name="role_description" id="role_description"></textarea>
                                </div>
    @endif
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
