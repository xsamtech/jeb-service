<!DOCTYPE html>
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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/addons/cropper/css/cropper.min.css') }}">

        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
        <style>
            * { font-family: "Plus Jakarta Sans", sans-serif }
            textarea { resize: none; }
            th, td, .form-label { font-size: 14px }
            .user-account { text-decoration: none; color: #000; }
            .navbar-toggler { position: absolute; right: 1rem; z-index: 9999; }
            .navbar-toggler:focus { box-shadow: none!important; }
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
        <!-- ### Crop user image ### -->
        <div class="modal fade" id="cropModalUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header py-0">
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
                    <div class="modal-footer pb-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill text-white" data-bs-dismiss="modal">@lang('miscellaneous.cancel')</button>
                        <button type="button" id="crop_avatar" class="btn btn-primary px-4 rounded-pill"data-bs-dismiss="modal">{{ __('miscellaneous.register') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODALS-->

        <main class="flex-shrink-0">
            <div id="ajax-alert-container"></div>
@if (\Session::has('success_message'))
            <!-- Alert Start -->
            <div class="position-relative">
                <div class="row position-fixed w-100" style="opacity: 0.9; z-index: 999;">
                    <div class="col-lg-4 col-sm-6 mx-auto">
                        <div class="alert alert-success alert-dismissible fade show rounded-0 cnpr-line-height-1_1" role="alert">
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
                        <div class="alert alert-danger alert-dismissible fade show rounded-0 cnpr-line-height-1_1" role="alert">
                            <i class="bi bi-exclamation-triangle me-2 fs-4" style="vertical-align: -3px;"></i> {!! \Session::get('error_message') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alert End -->
@endif

            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
                <div class="container-fluid container-sm px-lg-5 position-relative">
                    <a class="navbar-brand" href="{{ route('dashboard.home') }}">
                        <img src="{{ asset('assets/img/brand.png') }}" alt="Logo" width="150">
                    </a>
                    <a href="{{ route('dashboard.account') }}" class="position-absolute d-lg-none d-inline-block me-2 rounded-circle user-account user-image" style="top: 2rem; right: 3rem;">
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
                                <a class="nav-link{{ Route::is('dashboard.panels') ? ' active' : '' }}" role="button" href="{{ route('dashboard.panels') }}">
                                    Panneaux
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.users.entity') ? ' active' : '' }}" role="button" href="{{ route('dashboard.users.entity', ['entity' => 'orders']) }}">
                                    Commandes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ Route::is('dashboard.expenses') ? ' active' : '' }}" role="button" href="{{ route('dashboard.expenses') }}">
                                    Dépenses
                                </a>
                            </li>
                            <li class="nav-item d-lg-none d-inline-block">
                                <form action="{{ route('logout') }}" method="POST">
@csrf
                                    <button class="nav-link border-0 bg-transparent py-2">Quitte la session</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown position-relative">
                        <a role="button" class="d-lg-inline-block d-none flex-row ms-5 my-3 rounded-pill user-account user-image" data-bs-toggle="dropdown" aria-expanded="false">
                            <strong class="d-inline-block text-gradient">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</strong>
                            <img src="{{ !empty(Auth::user()->avatar_url) ? Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" width="50" class="ms-1 rounded-circle img-thumbnail">
                        </a>

                        <ul class="dropdown-menu position-absolute" style="right: 0; top: 3.1rem;">
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
        <script src="{{ asset('assets/addons/cropper/js/cropper.min.js') }}"></script>
        <script src="{{ asset('assets/addons/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/addons/bootstrap/js/bootstrap.bundle.js') }}"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <!-- Custom JS-->
        <script src="{{ asset('assets/js/scripts.custom.js') }}"></script>
    </body>
</html>
