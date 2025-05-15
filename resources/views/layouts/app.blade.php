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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
        <style>
            textarea { resize: none; }
            .user-account { text-decoration: none; color: #000; }
            .navbar-toggler:focus { box-shadow: none!important; }
            @media (min-width: 992px) {
                #navbarLinksContent { position: relative; top: -1rem; }
                .user-account { position: relative; top: -0.8rem; }
            }
        </style>

        <title>
@if (!empty($page_title))
            JEB Service / {{ $page_title }}
@else
            {{ env('APP_NAME') }}
@endif
        </title>
    </head>

    <body class="d-flex flex-column h-100">
        <main class="flex-shrink-0">
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
                <div class="container-fluid container-sm px-5 position-relative">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/brand.png') }}" alt="Logo" width="150">
                    </a>
                    <a role="button" class="position-absolute d-lg-none d-inline-block me-2 rounded-circle user-account" style="top: 2rem; right: 4.7rem;">
                        <img src="{{ asset('assets/img/user.png') }}" alt="Patrice Mubiayi" width="46" class="rounded-circle">
                    </a>
                    <button class="navbar-toggler px-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinksContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarLinksContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder align-top">
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.home') }}">Tableau de bord</a></li>
                            <li class="nav-item"><a role="button" class="nav-link">Clients</a></li>
                            <li class="nav-item"><a role="button" class="nav-link">Messages</a></li>
                        </ul>
                    </div>
                    <a role="button" class="d-lg-inline-block d-none flex-row ms-5 my-3 rounded-pill user-account">
                        <strong class="d-inline-block text-gradient">Patrice Mubiayi</strong>
                        <img src="{{ asset('assets/img/user.png') }}" alt="Patrice Mubiayi" width="50" class="ms-1 rounded-circle">
                    </a>
                </div>
            </nav>

@yield('app-content')

        </main>

        <!-- Footer-->
        <footer class="bg-white py-4 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0">Copyright &copy; {{ date('Y') }} JEB Service</div></div>
                    <div class="col-auto"><div class="small m-0">Designed by <a href="https://xsamtech.com" target="_blank">Xsam Technologies</a></div></div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
    </body>
</html>
