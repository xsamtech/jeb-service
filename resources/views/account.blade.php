@extends('layouts.app', ['page_title' => Route::is('dashboard.account.settings') ? 'Paramètres du compte' : 'Mon profil'])

@section('app-content')

            <section class="pb-3">
                <div class="container-lg container-fluid px-sm-5">
                    <div class="row g-lg-4 g-3">
                        <div class="col-lg-4 col-sm-5 mx-auto">
                            <div class="card border mb-3 rounded-4">
                                <div class="card-body text-center">
                                    <div class="bg-image mb-3 position-relative">
                                        <img src="{{ !empty(Auth::user()->avatar_url) ? getWebURL() . '/storage/' . Auth::user()->avatar_url : asset('assets/img/user.png') }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}" class="user-image img-fluid img-thumbnail rounded-4">
    @if (Route::is('dashboard.account.settings'))
                                        <form method="POST">
                                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                                            <label for="avatar" class="btn btn-secondary position-absolute pt-2 rounded-circle text-white" style="width: 2.5rem; height: 2.5rem; top: 0.5rem; left: 0.5rem; z-index: 999;" title="@lang('miscellaneous.change_image')" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                <span class="bi bi-pencil-fill"></span>
                                                <input type="file" name="avatar" id="avatar" class="d-none">
                                            </label>
                                        </form>
    @endif
                                    </div>

                                    <h3 class="h3 m-0 fw-bold">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h3>
    @if (!empty(Auth::user()->username))
                                    <p class="card-text m-0 text-muted">{{ '@' . Auth::user()->username }}</p>
    @endif
                                </div>
                            </div>

                            <div class="list-group">
                                <a href="{{ route('dashboard.account') }}" class="list-group-item list-group-item-action{{ Route::is('dashboard.account') ? ' active' : '' }}">
                                    <i class="bi bi-person-lines-fill me-3 fs-5 align-middle"></i>@lang('miscellaneous.account.personal_infos.title')
                                </a>
                                <a href="{{ route('dashboard.account.settings') }}" class="list-group-item list-group-item-action{{ Route::is('dashboard.account.settings') ? ' active' : '' }}">
                                    <i class="bi bi-gear me-3 fs-5 align-middle"></i>Paramètres du compte
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-8 col-sm-7 col-12">
    @if (Route::is('dashboard.account.settings'))
        @include('partials.account.settings')
    @else
        @include('partials.account.home')
    @endif
                        </div>
                    </div>
                </div>
            </section>

@endsection
