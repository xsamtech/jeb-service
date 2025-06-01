@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : 'Gérer les utilisateurs'])

@section('app-content')

            <!-- Title Section-->
            <section class="pb-3">
                <div class="container px-lg-5 px-sm-4">
                    <div class="d-flex justify-content-lg-between justify-content-center flex-lg-row flex-column align-items-center align-items-end mt-4">
                        <h1 class="fw-bolder py-lg-0 py-3 mb-0"><span class="text-gradient d-inline">{{ !empty($entity_title) ? $entity_title : 'Gérer les utilisateurs' }}</span></h1>
                        <div class="flex-row text-center">
    @if (Route::is('dashboard.users.entity'))
        @if ($entity == 'roles')
                            <button class="btn btn-sm btn-outline-dark me-sm-2 pb-sm-1 float-end" data-bs-toggle="modal" data-bs-target="#userEntityModal">Nouveau rôle</button>
        @endif

        @if ($entity == 'orders')
                            <button class="btn btn-sm btn-outline-dark me-sm-2 pb-sm-1 float-end" data-bs-toggle="modal" data-bs-target="#userEntityModal">Nouvelle commande</button>
        @endif
                            <a href="{{ route('dashboard.users') }}" class="btn btn-secondary btn-sm pb-sm-1 me-1 float-end text-white">
                                <i class="bi bi-chevron-double-left me-2"></i>Retour
                            </a>
    @else
                            <a href="{{ route('dashboard.users.entity', ['entity' => 'roles']) }}" class="btn btn-sm btn-primary pb-sm-1">Rôles</a>
                            <a href="{{ route('dashboard.users.entity', ['entity' => 'orders']) }}" class="btn btn-sm btn-secondary pb-sm-1 text-white">Commandes</a>
                            <br class="d-sm-none d-block">
                            <button class="btn btn-sm btn-outline-dark mt-lg-0 mt-1 pb-sm-1" data-bs-toggle="modal" data-bs-target="#userModal">Ajouter un administrateur</button>
    @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Content Section-->
            <section class="pb-3">
                <div class="container px-lg-5">
    @if (Route::is('dashboard.users.entity'))
        @include('partials.users.' . $entity)
    @else
        @include('partials.users.home')
    @endif
                </div>
            </section>

@endsection
