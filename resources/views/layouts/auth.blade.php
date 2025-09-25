<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="jebservice.com">
        <meta name="keywords" content="jeb,service,jebservice">
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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/cropper/css/cropper.min.css') }}">

        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/mdb/css/mdb.dark.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
        <style>
            textarea { resize: none; }
            .form-check-label { color: var(--bs-gray-500); }
            .card { border-color: var(--bs-gray-700); }
            .col-auto { color: var(--bs-white); }
            @media (min-width: 992px) {
                #navbarLinksContent { position: relative; top: -2.25rem; }
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
        <!-- ### Crop other user image ### -->
        <div class="modal fade" id="cropModal_profile" tabindex="-1" aria-hidden="true" data-bs-backdrop="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0">
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
        <!-- MODALS-->

        <main class="flex-shrink-0">
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-dark py-3">
                <div class="container px-5">
                    <a class="navbar-brand" href="{{ route('dashboard.home') }}">
                        <img src="{{ asset('assets/img/brand-dark.png') }}" alt="Logo" width="150">
                    </a>
@empty($exception)
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinksContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarLinksContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder align-top">
                            {{-- <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li> --}}
    @if (Route::is('login'))
        @if (!$admins_exist)
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @endif
    @endif
                        </ul>
                    </div>
@endempty
                </div>
            </nav>

@yield('auth-content')

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
        <!-- Bootstrap core JS-->
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> --}}
        <script src="{{ asset('assets/addons/bootstrap/js/bootstrap.bundle.js') }}"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <!-- Custom JS-->
        <script src="{{ asset('assets/js/scripts.custom.js') }}"></script>
    </body>
</html>
