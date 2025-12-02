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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/mdb/css/mdb.dark.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
        <style>
            * { font-family: "Plus Jakarta Sans", sans-serif }
            textarea { resize: none; }
            form { margin: 0; }
            table { caption-side: top; /* S'assure que le titre est en haut de la table */ }
            caption { font-size: 1.2em; font-weight: bold; color: #333; text-align: center; margin-bottom: 10px; }
            th, td, .form-label { font-size: 14px; }
            td { padding-top: 1rem!important; padding-bottom: 1rem!important; }
            th, td, .table { background-color: transparent!important; }
            .form-select { color: var(--bs-gray-500); background-color: transparent!important; border-color: var(--bs-gray-500)!important; }
            .form-select:focus, .form-select-dropdown { background-color: var(--bs-dark)!important; }
            .form-select option { color: var(--bs-gray-500); background-color: var(--bs-dark)!important; }
            .user-account { text-decoration: none; }
            .navbar-toggler { position: absolute; right: 1rem; z-index: 9997; }
            .modal { z-index: 9998; }
            .alert { z-index: 9999; }
            .navbar-toggler:focus { box-shadow: none!important; }
            .btn-check + label i { display: none; /* Caché par défaut */ }
            .btn-check:checked + label i { display: inline; /* Visible quand coché */ }
            .form-check-label { color: var(--bs-gray-500); }
            .card { border-color: var(--bs-gray-700)!important; }
            .modal-header { border-bottom-color: var(--bs-gray-500)!important; }
            .pagination .page-item.active .page-link { color: #fff; box-shadow: none; }
            .col-auto, .list-group-item { color: var(--bs-white)!important; }
            #tableHeader .card { font-size: 11px; text-transform: uppercase; }
            #tableBody .card { font-size: 13px; }
            #tableFooter .pagination { display: flex; justify-content: center; }
            #availablePanels.is-invalid { border-color: #dc3545 !important; box-shadow: 0 0 0 0.25rem rgba(220,53,69,.25); }
            @media (min-width: 992px) {
                #navbarLinksContent { position: relative; top: -1rem; }
                .user-account { position: relative; top: -0.8rem; }
            }
            @media (min-width: 480px) {
                #tableBody .taxe-implantation { min-height: 6.7rem!important; }
                #tableBody .panel-column { border-color: transparent!important; border-left-color: var(--bs-gray-700)!important; }
                #tableBody .face-column { border-color: transparent!important; border-top-color: var(--bs-gray-700)!important; border-left-color: var(--bs-gray-700)!important; }
            }
            @media (max-width: 480px) {
                #tableBody .panel-column { border-color: transparent!important; border-top-color: var(--bs-gray-700)!important; }
            }
        </style>

        <title>
@if (!empty($page_title))
            {{ $page_title }}
@else
            JEB Services
@endif
        </title>
    </head>

    <body class="d-flex flex-column h-100">
        <!-- MODALS-->
@include('layouts.modals')
        <!-- MODALS-->

        <main class="flex-shrink-0">
            <div id="ajax-alert-container"></div>
            <!-- Alert End -->
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
            <nav class="navbar navbar-expand-sm navbar-dark py-3">
                <div class="container-fluid container-lg px-lg-5 position-relative">
                    <a class="navbar-brand" href="{{ route('dashboard.home') }}">
                        <img src="{{ asset('assets/img/brand-dark.png') }}" alt="Logo" width="150">
                    </a>
                    <a href="{{ route('dashboard.account') }}" class="position-absolute d-sm-none d-inline-block me-2 rounded-circle user-account user-image" style="top: 2rem; right: 3rem;">
                        <img src="{{ !empty(Auth::user()->avatar_url) ? Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" width="46" class="rounded-circle">
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
@csrf
                        <button class="btn position-absolute d-sm-none d-inline-block px-0 border-0 text-light" type="submit" style="right: 1rem; top: 2.4rem;"><i class="bi bi-power fs-4"></i></button>
                    </form>
                    <div class="dropdown position-relative">
                        <a role="button" class="d-sm-inline-block d-none flex-row ms-5 my-3 rounded-pill user-account user-image" data-bs-toggle="dropdown" aria-expanded="false">
                            <strong class="d-lg-inline-block d-none text-gradient">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</strong>
                            <img src="{{ !empty(Auth::user()->avatar_url) ? Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" width="50" class="ms-1 rounded-circle">
                        </a>

                        <ul class="dropdown-menu bg-dark position-absolute py-0" style="right: 0; top: 3.1rem; width: 12rem;">
                            <li><a class="dropdown-item py-3" href="{{ route('dashboard.account') }}"><i class="bi bi-person me-2"></i>Mon compte</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
@csrf
                                    <button class="dropdown-item py-3"><i class="bi bi-power me-2"></i>Quitter la session</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

@yield('sheet-content')

        </main>

        <!-- Footer-->
        <footer class="py-4 mt-auto">
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
             * Listens for the click event on the ".switch-view" button
             */
            $('.switch-view').click(function() {
                // e.preventDefault();

                // Find the parent of each ".switch-view" button
                var container = $(this).closest('.card-body');

                // Toggle the ".show-data" and ".update-data" blocks
                container.find('.show-data').toggleClass('d-none');
                container.find('.update-data').toggleClass('d-none');
            });

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

            $(function () {
                /*
                 * All about USER/PANEL
                 */
                // Focus to specific input for each concerned modal
                $('#userModal').on('shown.bs.modal', function () {
                    $('#firstname').focus();
                });

                // Focus to specific input for each concerned modal
                $('#panelModal').on('shown.bs.modal', function () {
                    $('#dimensions').focus();
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
                            showAlert(true, 'Administrateur ajouté avec succès.');

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
                                $('#userModal').modal('hide');
                                // Error (500) alert
                                showAlert(false, 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
                            }
                        }
                    });
                });

                // Send panel registration
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
    </body>
</html>
