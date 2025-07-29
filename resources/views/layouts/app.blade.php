<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="jebservice.com">
        <meta name="keywords" content="jeb,service,jebservice">
        <meta name="jeb-url" content="{{ getWebURL() }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">

        <!-- Custom Google font-->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" />

        <!-- Bootstrap icons-->
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> --}}
        <link rel="stylesheet" href="{{ asset('assets/addons/bootstrap-icons/font/bootstrap-icons.min.css') }}">

        <!-- Addons CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/jquery/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/sweetalert2/dist/sweetalert2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/jquery/datetimepicker/css/jquery.datetimepicker.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/cropper/css/cropper.min.css') }}">

        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
        <style>
            * { font-family: "Plus Jakarta Sans", sans-serif }
            textarea { resize: none; }
            form { margin: 0; }
            table { caption-side: top; /* S'assure que le titre est en haut de la table */ }
            caption { font-size: 1.2em; font-weight: bold; color: #333; text-align: center; margin-bottom: 10px; }
            th, td, .form-label { font-size: 14px }
            .user-account { text-decoration: none; color: #000; }
            .navbar-toggler { position: absolute; right: 1rem; z-index: 9997; }
            .modal { z-index: 9998; }
            .alert { z-index: 9999; }
            .navbar-toggler:focus { box-shadow: none!important; }
            .btn-check + label i { display: none; /* Caché par défaut */ }
            .btn-check:checked + label i { display: inline; /* Visible quand coché */ }
            #availablePanels.is-invalid { border-color: #dc3545 !important; box-shadow: 0 0 0 0.25rem rgba(220,53,69,.25); }
            @media (min-width: 992px) {
                #navbarLinksContent { position: relative; top: -1rem; }
                .user-account { position: relative; top: -0.8rem; }
            }
        </style>

        <title>
@if (!empty($page_title))
            {{ $page_title }}
@else
            {{ env('APP_NAME') }}
@endif
        </title>
    </head>

    <body class="d-flex flex-column h-100">
        <!-- MODALS-->
@include('layouts.modals')
        <!-- MODALS-->

        <main class="flex-shrink-0">
@if (Route::is('dashboard.users.entity') && $entity == 'orders')
            <div id="ajax-loader" class="d-flex w-100 h-100 justify-content-center align-items-center d-none position-absolute top-0 start-0" style="z-index: 999; background: rgba(0, 0, 0, 0.3);">
                <img src="{{ asset('assets/img/ajax-loading.gif') }}" alt="Chargement..." width="100" height="100" height="32">
            </div>
@endif
            <div id="ajax-alert-container"></div>
@if (\Session::has('success_message'))
            <!-- Alert Start -->
            <div class="position-relative">
                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                    <div class="col-lg-4 col-sm-6 mx-auto">
                        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                            <i class="bi bi-info-circle me-2 fs-4" style="vertical-align: -3px;"></i> {!! \Session::get('success_message') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alert End -->
@endif
@if (\Session::has('error_message'))
            <!-- Alert Start -->
            <div class="position-relative">
                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                    <div class="col-lg-4 col-sm-6 mx-auto">
                        <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                            <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> {!! \Session::get('error_message') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alert End -->
@endif

            <!-- Navigation-->
            <nav class="navbar navbar-expand-sm navbar-light bg-white py-3">
                <div class="container-fluid container-lg px-lg-5 position-relative">
                    <a class="navbar-brand" href="{{ route('dashboard.home') }}">
                        <img src="{{ asset('assets/img/brand.png') }}" alt="Logo" width="150">
                    </a>
                    <a href="{{ route('dashboard.account') }}" class="position-absolute d-sm-none d-inline-block me-2 rounded-circle user-account user-image" style="top: 2rem; right: 3rem;">
                        <img src="{{ !empty(Auth::user()->avatar_url) ? Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" width="46" class="rounded-circle img-thumbnail">
                    </a>
                    <button class="navbar-toggler px-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinksContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarLinksContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder align-top">
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.home') ? ' active' : '' }}" href="{{ route('dashboard.home') }}">
                                    Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.users') ? ' active' : '' }}" role="button" href="{{ route('dashboard.users') }}">
                                    Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.panels') ? ' active' : '' }}" role="button" href="{{ route('dashboard.panels') }}">
                                    Panneaux
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.users.entity') && $entity == 'orders' ? ' active' : '' }}" role="button" href="{{ route('dashboard.users.entity', ['entity' => 'orders']) }}">
                                    Locations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.expenses') ? ' active' : '' }}" role="button" href="{{ route('dashboard.expenses') }}">
                                    Dépenses
                                </a>
                            </li>
                            <li class="nav-item d-sm-none d-inline-block">
                                <form action="{{ route('logout') }}" method="POST">
@csrf
                                    <button class="nav-link border-0 bg-transparent py-2">Quitte la session</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown position-relative">
                        <a role="button" class="d-sm-inline-block d-none flex-row ms-5 my-3 rounded-pill user-account user-image" data-bs-toggle="dropdown" aria-expanded="false">
                            <strong class="d-lg-inline-block d-none text-gradient">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</strong>
                            <img src="{{ !empty(Auth::user()->avatar_url) ? Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" width="50" class="ms-1 rounded-circle img-thumbnail">
                        </a>

                        <ul class="dropdown-menu position-absolute" style="right: 0; top: 3.1rem; width: 12rem;">
                            <li><a class="dropdown-item" href="{{ route('dashboard.account') }}"><i class="bi bi-person me-2"></i>Mon compte</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
@csrf
                                    <button class="dropdown-item"><i class="bi bi-power me-2"></i>Quitter la session</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

@yield('app-content')

        </main>

        <!-- Footer-->
        <footer class="bg-white py-4 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0">Copyright &copy; {{ date('Y') }} JEB Services</div></div>
                    <div class="col-auto"><div class="small m-0">Designed by <a href="https://xsamtech.com" target="_blank">Xsam Technologies</a></div></div>
                </div>
            </div>
        </footer>

        <!-- Addons JS-->
        <script src="{{ asset('assets/addons/jquery/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/addons/jquery/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/addons/autosize/js/autosize.min.js') }}"></script>
        <script src="{{ asset('assets/addons/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/addons/jquery/datetimepicker/js/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{ asset('assets/addons/cropper/js/cropper.min.js') }}"></script>
        <script src="{{ asset('assets/addons/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/addons/bootstrap/js/bootstrap.bundle.js') }}"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <!-- Custom JS-->
        <script src="{{ asset('assets/js/scripts.custom.js') }}"></script>
        <script type="text/javascript">
            /**
             * Perform action
             * 
             * @param boolean success
             * @param string message
             */
            function performAction(action, entity, entity_id) {
                if (action === 'delete') {
                    var entityId = parseInt(entity_id.split('-')[1]);

                    Swal.fire({
                        title: 'Attention suppression',
                        text: 'Voulez-vous vraiment supprimer ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'

                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: headers,
                                type: 'DELETE',
                                url: `${currentHost}/delete/${entity}/${entityId}`,
                                contentType: false,
                                processData: false,
                                data: JSON.stringify({ 'entity' : entity, 'id' : entityId }),
                                success: function (result) {
                                    if (!result.success) {
                                        Swal.fire({
                                            title: 'Oups !',
                                            text: result.message,
                                            icon: 'error'
                                        });

                                    } else {
                                        Swal.fire({
                                            title: 'Parfait !',
                                            text: result.message,
                                            icon: 'success'
                                        });
                                        location.reload();
                                    }
                                },
                                error: function (xhr, error, status_description) {
                                    console.log(xhr.responseJSON);
                                    console.log(xhr.status);
                                    console.log(error);
                                    console.log(status_description);
                                }
                            });

                        } else {
                            Swal.fire({
                                title: 'Annuler',
                                text: 'Suppression annulée',
                                icon: 'error'
                            });
                        }
                    });
                }
            }

            /**
             * Check string is numeric
             * 
             * @param boolean success
             * @param string message
             */
            const showAlert = (success, message) => {
                const color = success === true ? 'success' : 'danger';
                const icon = success === true ? 'bi bi-info-circle' : 'bi bi-exclamation-triangle';

                // Delete old alerts
                $('#ajax-alert-container .alert').alert('close');

                const alert = `<div class="position-relative">
                                    <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                                        <div class="col-lg-4 col-sm-6 mx-auto">
                                            <div class="alert alert-${color} alert-dismissible fade show rounded-0" role="alert">
                                                <i class="${icon} me-2 fs-4" style="vertical-align: -3px;"></i> ${message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                // Adding alert to do DOM
                $('#ajax-alert-container').append(alert);

                // Automatic closing after 6 seconds
                setTimeout(() => {
                    $('#ajax-alert-container .alert').alert('close');
                }, 6000);
            };
        </script>
@if (Route::is('dashboard.users'))
        <script type="text/javascript">
            $(function () {
                /*
                 * All about USER
                 */
                // Focus to specific input for each concerned modal
                $('#userModal').on('shown.bs.modal', function () {
                    $('#firstname').focus();
                });
                // Send user registration
                $('#addUserForm').on('submit', function (e) {
                    e.preventDefault();

                    // Clean up previous errors and alerts
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Show the loader
                    $('#ajax-loader').removeClass('d-none');

                    const formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Hide the loader
                            $('#ajax-loader').addClass('d-none');
                            // Close the modal
                            $('#userModal').modal('hide');

                            // Success alert
                            showAlert(true, 'Utilisateur ajouté avec succès.');

                            // Just reload the table
                            $('#dataList').load(location.href + ' #dataList > *');
                            // Reset the form
                            $('#addUserForm')[0].reset();
                            // Reset profile picture
                            $('#image_64').val('');
                            $('.other-user-image').attr('src', `${currentHost}/assets/img/user.png`);
                            // Remove error classes (just in case)
                            $('#addUserForm .is-invalid').removeClass('is-invalid');
                            $('#addUserForm .invalid-feedback').remove();
                        },
                        error: function (xhr) {
                            $('#ajax-loader').addClass('d-none');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                for (const [field, messages] of Object.entries(errors)) {
                                    const input = $(`[name="${field}"]`);

                                    input.addClass('is-invalid');
                                    input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                }

                            } else {
                                // Close the modal
                                $('#userModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });
            });
        </script>
@endif

@if (Route::is('dashboard.users.entity'))
    @if ($entity == 'roles')
        <script type="text/javascript">
            $(function () {
                /*
                 * All about ROLE
                 */
                // Focus to specific input for each concerned modal
                $('#userEntityModal').on('shown.bs.modal', function () {
                    $('#role_name').focus();
                });
                // Send user registration
                $('#addRoleForm').on('submit', function (e) {
                    e.preventDefault();

                    // Clean up previous errors and alerts
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Show the loader
                    $('#ajax-loader').removeClass('d-none');

                    const formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Hide the loader
                            $('#ajax-loader').addClass('d-none');
                            // Close the modal
                            $('#userEntityModal').modal('hide');

                            // Success alert
                            showAlert(true, 'Rôle ajouté avec succès.');

                            // Just reload the table
                            $('#dataList').load(location.href + ' #dataList > *');
                            // Reset the form
                            $('#addUserForm')[0].reset();
                            // Remove error classes (just in case)
                            $('#addUserForm .is-invalid').removeClass('is-invalid');
                            $('#addUserForm .invalid-feedback').remove();
                        },
                        error: function (xhr) {
                            $('#ajax-loader').addClass('d-none');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                for (const [field, messages] of Object.entries(errors)) {
                                    const input = $(`[name="${field}"]`);

                                    input.addClass('is-invalid');
                                    input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                }

                            } else {
                                // Close the modal
                                $('#userEntityModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });
            });
        </script>
    @endif

    @if ($entity == 'orders')
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('userSearchInput');
                const resultContainer = document.querySelector('#searchUserModal .list-group');
                const initialResultHTML = `<p class="mb-0 text-center"><i class="bi bi-search fs-1"></i></p>
                                            <p class="lead mb-0 text-center">La liste s'affiche ici.</p>`;
                let typingTimer;

                input.addEventListener('keyup', function () {
                    clearTimeout(typingTimer);

                    typingTimer = setTimeout(() => {
                        const query = input.value.trim();

                        if (query.length < 1) {
                            resultContainer.innerHTML = initialResultHTML;

                            return;
                        }

                        // Afficher le loader
                        resultContainer.innerHTML = `<div class="text-center my-3">
                                                        <img src="${currentHost}/assets/img/ajax-loading.gif" alt="Chargement..." width="70">
                                                    </div>`;

                        fetch(`/users/search?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                resultContainer.innerHTML = '';

                                data.data.forEach(user => {
                                    const item = document.createElement('a');

                                    item.className = 'list-group-item list-group-item-action';
                                    item.setAttribute('role', 'button');
                                    item.dataset.firstname = user.firstname;
                                    item.dataset.lastname = user.lastname;
                                    item.dataset.email = user.email;
                                    item.dataset.phone = user.phone;
                                    item.dataset.avatar = user.avatar_url;

                                    item.innerHTML = `<div class="d-flex flex-row align-items-center">
                                                        <div class="bg-image">
                                                            <img src="${user.avatar_url}" alt="" width="70" class="rounded-circle">
                                                        </div>
                                                        <div class="ms-3">
                                                            <h5 class="mb-0 fw-bold">${user.firstname} ${user.lastname}</h5>
                                                            <p class="small mb-0 text-muted"><i class="bi bi-envelope-fill me-2 fs-6"></i>${user.email}</p>
                                                            <p class="small mb-0 text-muted"><i class="bi bi-telephone-fill me-2 fs-6"></i>${user.phone}</p>
                                                        </div>
                                                    </div>`;

                                    resultContainer.appendChild(item);
                                });

                            } else {
                                resultContainer.innerHTML = `<p class="mb-0 text-center"><i class="bi bi-search fs-1"></i></p>
                                                                <p class="lead mb-0 text-center">Aucun utilisateur trouvé.</p>`;
                            }
                        });
                    }, 300);
                });

                // Gérer le clic sur un utilisateur
                resultContainer.addEventListener('click', function (e) {
                    const target = e.target.closest('.list-group-item');

                    if (!target) return;

                    // Remplir le modal principal
                    document.getElementById('firstname').value = target.dataset.firstname;
                    document.getElementById('lastname').value = target.dataset.lastname;
                    document.getElementById('email').value = target.dataset.email;
                    document.getElementById('customer_phone_hidden').value = target.dataset.phone;
                    document.getElementById('phone').value = target.dataset.phone;
                    // Changer l'image de profil
                    document.querySelector('.other-user-image').src = target.dataset.avatar;

                    // Fermer le modal de recherche et ouvrir l'autre
                    const modalSearch = bootstrap.Modal.getInstance(document.getElementById('searchUserModal'));
                    const userModal = new bootstrap.Modal(document.getElementById('userEntityModal'));

                    modalSearch.hide();
                    userModal.show();
                });
            });

            $(function () {
                /*
                 * All about ORDER
                 */
                // Focus to specific input for each concerned modal
                $('#userEntityModal').on('shown.bs.modal', function () {
                    $('#firstname').focus();
                });
                // Send user registration
                $('#addOrderForm').on('submit', function (e) {
                    e.preventDefault();

                    // Clean up previous errors and alerts
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Show the loader
                    $('#ajax-loader').removeClass('d-none');

                    const formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Hide the loader
                            $('#ajax-loader').addClass('d-none');
                            // Close the modal
                            $('#userEntityModal').modal('hide');

                            // Success alert
                            showAlert(true, 'Location ajoutée avec succès.');

                            // Just reload the table
                            $('#dataList').load(location.href + ' #dataList > *');
                            // Reset the form
                            $('#addOrderForm')[0].reset();
                            // Remove error classes (just in case)
                            $('#addOrderForm .is-invalid').removeClass('is-invalid');
                            $('#addOrderForm .invalid-feedback').remove();
                        },
                        error: function (xhr) {
                            $('#ajax-loader').addClass('d-none');

                            if (xhr.status === 422) {
                                const response = xhr.responseJSON;

                                if (response && response.errors) {
                                    const errors = response.errors;

                                    for (const [field, messages] of Object.entries(errors)) {
                                        if (field === 'panels_ids') {
                                            $('#availablePanels').removeClass('is-invalid');
                                            $('#availablePanels').next('.invalid-feedback').remove();

                                            $('#availablePanels').addClass('is-invalid');
                                            $('#availablePanels').after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);

                                        } else {
                                            const input = $(`[name="${field}"]`);

                                            if (input.length > 0) {
                                                input.addClass('is-invalid');
                                                input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                            }
                                        }
                                    }

                                } else {
                                    // Pas de champs spécifiques ? Afficher le message général
                                    if (response.message) {
                                        // Supprime d'abord un ancien message, s'il existe
                                        $('#availablePanels').removeClass('is-invalid');
                                        $('#availablePanels').next('.invalid-feedback').remove();

                                        // Ajoute la classe d'erreur visuelle
                                        $('#availablePanels').addClass('is-invalid');

                                        // Affiche le message juste après la div
                                        $('#availablePanels').after(`<div class="invalid-feedback d-block">${response.message}</div>`);

                                    } else {
                                        showAlert(false, 'Une erreur de validation est survenue.');
                                    }
                                }


                            } else {
                                // Close the modal
                                $('#userEntityModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });

            });
        </script>
    @endif
@endif

@if (Route::is('dashboard.panels'))
        <script type="text/javascript">
            $(function () {
                /*
                 * All about USER
                 */
                // Focus to specific input for each concerned modal
                $('#panelModal').on('shown.bs.modal', function () {
                    $('#dimensions').focus();
                });
                // Send user registration
                $('#addPanelForm').on('submit', function (e) {
                    e.preventDefault();

                    // Clean up previous errors and alerts
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Show the loader
                    $('#ajax-loader').removeClass('d-none');

                    const formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Hide the loader
                            $('#ajax-loader').addClass('d-none');
                            // Close the modal
                            $('#panelModal').modal('hide');

                            // Success alert
                            showAlert(true, 'Panneau ajouté avec succès.');

                            // Just reload the table
                            $('#dataList').load(location.href + ' #dataList > *');
                            // Reset the form
                            $('#addPanelForm')[0].reset();
                            // Remove error classes (just in case)
                            $('#addPanelForm .is-invalid').removeClass('is-invalid');
                            $('#addPanelForm .invalid-feedback').remove();
                        },
                        error: function (xhr) {
                            $('#ajax-loader').addClass('d-none');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                for (const [field, messages] of Object.entries(errors)) {
                                    const input = $(`[name="${field}"]`);

                                    input.addClass('is-invalid');
                                    input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                }

                            } else {
                                // Close the modal
                                $('#panelModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });
            });
        </script>
@endif

@if (Route::is('dashboard.expenses'))
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                // Initialisation des modals Bootstrap
                const ordersListModal = new bootstrap.Modal('#ordersListModal', { keyboard: false });
                const expenseModal = new bootstrap.Modal('#expenseModal', { keyboard: false });

                // Ouverture du modal Orders et chargement de la première page des commandes
                $('#ordersListModal').on('shown.bs.modal', () => {
                    loadOrders(1); // Charger la première page des commandes
                });

                // Fonction pour charger la liste des commandes
                function loadOrders(page) {
                    fetch(`${currentHost}/orders?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        // Mettre à jour la liste des commandes
                        const orderList = document.getElementById('ordersList');

                        orderList.innerHTML = ''; // Réinitialiser la liste

                        data.orders.forEach(order => {
                            const orderItem = document.createElement('div');
                            const orderFace = (order.face && order.face.face_name ? order.face.face_name.toUpperCase() : '');

                            orderItem.classList.add('order-item', 'btn', 'btn-sm', 'btn-light', 'text-start', 'mb-2', 'w-100');
                            orderItem.setAttribute('role', 'button');
                            orderItem.dataset.id = order.id;
                            orderItem.innerHTML = `${order.panel.location} (${orderFace})<br><strong>Le ${order.created_at_explicit}</strong>`;
                            orderList.appendChild(orderItem);

                            // Ajouter un event listener pour chaque commande
                            orderItem.addEventListener('click', () => {
                                loadExpenseDetails(order.id);
                            });
                        });

                        // Mettre à jour la pagination
                        const pagination = document.getElementById('pagination');

                        pagination.innerHTML = '';

                        for (let i = 1; i <= data.total_pages; i++) {
                            const pageButton = document.createElement('button');

                            pageButton.classList.add('btn', 'btn-success');

                            pageButton.textContent = 'Charger plus';

                            pageButton.addEventListener('click', function() {
                                loadOrders(i);
                            });
                            pagination.appendChild(pageButton);
                        }
                    });
                }

                // Fonction pour charger les détails de la commande et afficher dans le modal Expense
                function loadExpenseDetails(orderId) {
                    fetch(`${currentHost}/order/${orderId}`)
                    .then(response => response.json())
                    .then(order => {
                        const orderFace = (order.face && order.face.face_name ? order.face.face_name.toUpperCase() : '');

                        document.getElementById('location').textContent = `${order.panel.location} (${orderFace})`;
                        document.getElementById('created_at').textContent = order.created_at_explicit;
                        document.getElementById('user_fullname').textContent = `${order.user.firstname} ${order.user.lastname}`;
                        document.getElementById('order_id').value = order.id;

                        // Afficher les informations de la commande dans le formulaire
                        document.getElementById('selectedOrder').classList.remove('d-none')

                        // Fermer le modal Order et ouvrir le modal Expense
                        ordersListModal.hide();
                        expenseModal.show();
                    });
                }
            });

            $(function () {
                /*
                 * All about USER
                 */
                // Focus to specific input for each concerned modal
                $('#expenseModal').on('shown.bs.modal', function () {
                    $('#designation').focus();
                });
                // Send user registration
                $('#addExpenseForm').on('submit', function (e) {
                    e.preventDefault();

                    // Clean up previous errors and alerts
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // Show the loader
                    $('#ajax-loader').removeClass('d-none');

                    const formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Hide the loader
                            $('#ajax-loader').addClass('d-none');
                            // Close the modal
                            $('#expenseModal').modal('hide');

                            // Success alert
                            showAlert(true, 'Dépense ajoutée avec succès.');

                            // Just reload the table
                            $('#dataList').load(location.href + ' #dataList > *');
                            // Reset the form
                            $('#addExpenseForm')[0].reset();
                            // Remove error classes (just in case)
                            $('#addExpenseForm .is-invalid').removeClass('is-invalid');
                            $('#addExpenseForm .invalid-feedback').remove();
                        },
                        error: function (xhr) {
                            $('#ajax-loader').addClass('d-none');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;

                                for (const [field, messages] of Object.entries(errors)) {
                                    const input = $(`[name="${field}"]`);

                                    input.addClass('is-invalid');
                                    input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                }

                            } else {
                                // Close the modal
                                $('#expenseModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });
            });
        </script>
@endif
    </body>
</html>
