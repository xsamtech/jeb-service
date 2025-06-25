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
                                    <input type="text" name="firstname" class="form-control" id="firstname">
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
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#userEntityModal">{{ $entity == 'orders' ? 'Ajouter une location' : 'Ajouter un rôle' }}</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax-loader" class="position-absolute d-none" style="top: 10px; right: 10px;">
                            <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="32" height="32">
                        </div>
                        <form id="{{ $entity == 'orders' ? 'addOrderForm' : 'addRoleForm' }}" action="{{ route('dashboard.users.entity', ['entity' => $entity]) }}" method="POST">
        @csrf
                            <input type="hidden" id="customer_phone_hidden" name="customer_phone">

                            <div class="row g-2">
    @if ($entity == 'orders')
                                <!-- User data -->
                                <div class="col-lg-5">
                                    <div class="card card-body">
                                        <div class="d-flex{{ $count_customers > 0 ? ' justify-content-between' : ' justify-content-center text-center' }} position-relative" style="z-index: 999;">
                                            <p class="card-text fw-bold">Identité du client</p>
                                            <button type="button" class="btn btn-outline-dark{{ $count_customers > 0 ? '' : ' d-none' }}" title="Rechercher un client existant" data-bs-toggle="modal" data-bs-target="#searchUserModal"><i class="bi bi-search"></i></button>
                                        </div>
                                        <!-- Profile -->
                                        <div class="row g-3">
                                            <div class="col-lg-6 col-sm-8 mx-auto">
                                                <div id="profileImageWrapper" class="mb-3 position-relative">
                                                    <img src="{{ asset('assets/img/user.png') }}" alt="Avatar" class="other-user-image img-fluid img-thumbnail rounded-4">
                                                    <label role="button" for="image_profile" class="btn btn-secondary px-2 py-1 rounded-circle position-absolute end-0 bottom-0">
                                                        <i class="bi bi-pencil-fill text-white"></i>
                                                        <input type="file" name="image_profile" id="image_profile" class="d-none">
                                                    </label>
                                                    <input type="hidden" name="image_64" id="image_64">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- First name -->
                                        <div class="mb-2">
                                            <label for="firstname" class="form-label fw-bold visually-hidden">Prénom</label>
                                            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Prénom">
                                        </div>

                                        <!-- Last name -->
                                        <div class="mb-2">
                                            <label for="lastname" class="form-label fw-bold visually-hidden">Nom</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Nom">
                                        </div>

                                        <!-- E-mail -->
                                        <div class="mb-2">
                                            <label for="email" class="form-label fw-bold visually-hidden">E-mail</label>
                                            <input type="text" name="email" class="form-control" id="email" placeholder="E-mail">
                                        </div>

                                        <!-- Phone -->
                                        <div class="mb-2">
                                            <label for="phone" class="form-label fw-bold visually-hidden">N° de téléphone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="N° de téléphone">
                                        </div>
                                    </div>
                                </div>

                                <!-- Orders data -->
                                <div class="col-lg-7 mt-sm-0 mt-3">
                                    <small class="small mb-2 d-block text-center"><i class="bi bi-info-circle me-2"></i>Clic sur panneau pour sélectionner</small>
                                    <div id="availablePanels" class="card card-body border mb-3" style="max-height: 390px; overflow: auto;">
        @forelse ($available_panels as $panel)
                                        <input type="checkbox" class="btn-check" id="panelCheckbox{{ $panel['id'] }}" name="panels_ids[]" value="{{ $panel['id'] }}" autocomplete="off">

                                        <label class="btn btn-light mb-3 border text-start position-relative" for="panelCheckbox{{ $panel['id'] }}">
                                            <div class="card card-body border-0 bg-transparent p-0">
                                                <p class="card-text mb-1">{{ $panel['location'] }}</p>
                                                <p class="mb-2">
                                                    <span class="badge text-bg-dark fw-normal">{{ $panel['dimensions'] }}</span>
                                                </p>
                                                <small class="small mb-0"><u>Format</u> : {{ $panel['format'] }}</small><br>
                                                <small class="small"><u>Prix</u> : {{ $panel['price'] }}</small>
                                            </div>

                                            <!-- This icon will only be displayed when the checkbox is checked -->
                                            <i class="bi bi-check display-6 text-white position-absolute bottom-0 end-0"></i>
                                        </label>

                                        <!-- End date -->
                                        <div class="col-sm-6">
                                            <label for="outflow_date" class="form-label fw-bold">Date/Heure de fin de location</label>
                                            <input type="datetime" name="end_date[]" class="form-control" id="outflow_date">
                                        </div>
        @empty
                                        <h2 class="text-center fst-italic">Il n'y a pas de panneau disponible</h2>
        @endforelse
                                    </div>
                                </div>
    @else
                                <!-- Role name -->
                                <div class="col-12">
                                    <label for="role_name" class="form-label fw-bold">Nom du rôle</label>
                                    <input type="text" name="role_name" class="form-control" id="role_name">
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

        <!-- ### Search user ### -->
        <div class="modal fade" id="searchUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#userModal">Rechercher un client</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-target="#userEntityModal" data-bs-toggle="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <!-- Search input -->
                                <div class="col-lg-9 col-sm-11 mx-auto mb-sm-0 mb-4">
                                    <input type="search" id="userSearchInput" class="form-control" placeholder="Rechercher par nom">
                                </div>

                                <!-- Search result -->
                                <div class="col-12 mt-3 mb-2">
                                    <div class="list-group">
                                        <p class="mb-0 text-center"><i class="bi bi-search fs-1"></i></p>
                                        <p class="lead mb-0 text-center">La liste s'affiche ici.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-target="#userEntityModal" data-bs-toggle="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
@endif

@if (Route::is('dashboard.panels'))
        <!-- ### Add new admin ### -->
        <div class="modal fade" id="panelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#panelModal">Ajouter un panneau</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax-loader" class="position-absolute d-none" style="top: 10px; right: 10px;">
                            <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="32" height="32">
                        </div>
                        <form id="addPanelForm" action="{{ route('dashboard.panels') }}" method="POST">
    @csrf
                            <div class="row g-3">
                                <!-- Dimensions -->
                                <div class="col-sm-6">
                                    <label for="dimensions" class="form-label fw-bold">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control" id="dimensions">
                                </div>

                                <!-- Format -->
                                <div class="col-sm-6">
                                    <label for="format" class="form-label fw-bold">Format</label>
                                    <input type="text" name="format" class="form-control" id="format">
                                </div>

                                <!-- Price -->
                                <div class="col-sm-6">
                                    <label for="price" class="form-label fw-bold">Prix</label>
                                    <input type="number" step="0.01" name="price" class="form-control" id="price">
                                </div>

                                <!-- Location -->
                                <div class="col-sm-6">
                                    <label for="location" class="form-label fw-bold">Site / Emplacement</label>
                                    <textarea class="form-control" name="location" id="location"></textarea>
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
@endif

@if (Route::is('dashboard.expenses'))
        <!-- ### Add new admin ### -->
        <div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#expenseModal">Ajouter une dépense</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax-loader" class="position-absolute d-none" style="top: 10px; right: 10px;">
                            <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="32" height="32">
                        </div>
                        <form id="addExpenseForm" action="{{ route('dashboard.expenses') }}" method="POST">
    @csrf
                            <div class="row g-3">
                                <!-- Expense reason (designation) -->
                                <div class="col-sm-6">
                                    <label for="designation" class="form-label fw-bold">Motif de dépense</label>
                                    <input type="text" name="designation" class="form-control" id="designation">
                                </div>

                                <!-- Amount -->
                                <div class="col-sm-6">
                                    <label for="amount" class="form-label fw-bold">Montant</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" id="amount">
                                </div>

                                <!-- Outflow date -->
                                <div class="col-sm-6">
                                    <label for="outflow_date" class="form-label fw-bold">Date/Heure de sortie</label>
                                    <input type="datetime" name="outflow_date" class="form-control" id="outflow_date">
                                </div>

                                <!-- Add order -->
                                <div class="col-sm-6">
                                    <input type="hidden" id="order_id">
                                    <label class="form-label fw-bold">Associer à une location</label>
                                    <a role="button" id="openOrderModal" class="btn btn-sm btn-light border w-100" data-bs-toggle="modal" data-bs-target="#ordersListModal">Voir la liste</a>
                                </div>

                                <div id="selectedOrder" class="col-12 d-none">
                                    <div class="card card-body">
                                        <p class="card-text">Emplacements : <span id="location"></span></p>
                                        <p class="card-text">Date de commande : <span id="created_at"></span></p>
                                        <p class="card-text">Loué par : <span id="user_fullname"></span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                                <button type="submit" id="openOrdersModal" class="btn btn-primary px-4 rounded-pill">{{ __('miscellaneous.register') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ### Add new admin ### -->
        <div class="modal fade" id="ordersListModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="d-block modal-header bg-light text-center">
                        <h4 class="modal-title text-gradient fw-bold" aria-labelledby="#ordersListModal">Choisir la location</h4>
                        <button type="button" class="btn-close position-absolute" style="top: 1rem; right: 1rem;" data-bs-toggle="modal" data-bs-target="#expenseModal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body" style="max-height: 300px">
                        <div id="ordersList">
                            <div id="ajax-loader" class="text-center">
                                <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="100" height="100">
                            </div>
                        </div>
                        <div id="pagination" class="text-center"></div>
                    </div>
                </div>
            </div>
        </div>
@endif
