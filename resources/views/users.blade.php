@extends('layouts.app', ['page_title' => !empty($entity_title) ? $entity_title : 'Gérer les utilisateurs'])

@section('app-content')

            <!-- Title Section-->
            <section class="pb-3">
                <div class="container px-lg-5 px-sm-4">
                    <div class="d-flex justify-content-lg-between justify-content-center flex-lg-row flex-column align-items-center align-items-end mt-4">
                        <h1 class="fw-bolder py-lg-0 py-3 mb-0"><span class="text-gradient d-inline">{{ !empty($entity_title) ? $entity_title : 'Gérer les utilisateurs' }}</span></h1>
                        <div class="flex-row text-center">
    @if (Route::is('dashboard.users.entity'))
                            <a href="{{ route('dashboard.users') }}" class="btn btn-primary float-end">Retour</a>
        @if ($entity == 'roles')
                            <a class="btn btn-secondary me-sm-2 float-end text-white">Nouveau rôle</a>
        @endif

        @if ($entity == 'orders')
                            <a class="btn btn-secondary me-sm-2 float-end text-white">Nouvelle commande</a>
        @endif
    @else
                            <button class="btn btn-sm btn-outline-secondary pb-sm-1">Ajouter un administrateur</button><br class="d-sm-none d-block">
                            <a href="{{ route('dashboard.users.entity', ['entity' => 'roles']) }}" class="btn btn-sm btn-primary pb-sm-1">Rôles</a>
                            <a href="{{ route('dashboard.users.entity', ['entity' => 'orders']) }}" class="btn btn-sm btn-secondary pb-sm-1 text-white">Commandes</a>
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
