@extends('layouts.sheet', ['page_title' => (!empty($entity_title) ? $entity_title : 'JEB Services')])

@section('sheet-content')

            <!-- Content Section-->
            <section class="pb-3">
                <div class="container-fluid">
                    <div id="dataList" class="row px-lg-4">
                        <div class="col-md-12">
                            <div class="card card-body d-sm-flex flex-sm-row justify-content-between mb-3 border rounded-0">
@if (Route::is('dashboard.home.datas'))
                                <p class="mt-sm-0 mt-2 mb-0">{{ $entity_title }}</p>
@else
                                <!-- Choose a month -->
                                <form method="GET" class="form-search">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <select name="month" id="month" class="form-select form-select-sm">
                                                <option value="1"{{ request()->get('month') == '1' || \Carbon\Carbon::now()->month == 1 ? ' selected' : '' }}>Janvier</option>
                                                <option value="2"{{ request()->get('month') == '2' || \Carbon\Carbon::now()->month == 2 ? ' selected' : '' }}>Février</option>
                                                <option value="3"{{ request()->get('month') == '3' || \Carbon\Carbon::now()->month == 3 ? ' selected' : '' }}>Mars</option>
                                                <option value="4"{{ request()->get('month') == '4' || \Carbon\Carbon::now()->month == 4 ? ' selected' : '' }}>Avril</option>
                                                <option value="5"{{ request()->get('month') == '5' || \Carbon\Carbon::now()->month == 5 ? ' selected' : '' }}>Mai</option>
                                                <option value="6"{{ request()->get('month') == '6' || \Carbon\Carbon::now()->month == 6 ? ' selected' : '' }}>Juin</option>
                                                <option value="7"{{ request()->get('month') == '7' || \Carbon\Carbon::now()->month == 7 ? ' selected' : '' }}>Juillet</option>
                                                <option value="8"{{ request()->get('month') == '8' || \Carbon\Carbon::now()->month == 8 ? ' selected' : '' }}>Ao&ucirc;t</option>
                                                <option value="9"{{ request()->get('month') == '9' || \Carbon\Carbon::now()->month == 9 ? ' selected' : '' }}>Septembre</option>
                                                <option value="10"{{ request()->get('month') == '10' || \Carbon\Carbon::now()->month == 10 ? ' selected' : '' }}>Octobre</option>
                                                <option value="11"{{ request()->get('month') == '11' || \Carbon\Carbon::now()->month == 11 ? ' selected' : '' }}>Novembre</option>
                                                <option value="12"{{ request()->get('month') == '12' || \Carbon\Carbon::now()->month == 12 ? ' selected' : '' }}>Décembre</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <select name="year" id="year" class="form-select form-select-sm">
    @for ($i = 1900; $i < \Carbon\Carbon::now()->year + 1 ; $i++)
                                                <option {{ request()->get('year') == $i || \Carbon\Carbon::now()->year == $i ? ' selected' : '' }}>{{ $i }}</option>
    @endfor
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <input type="submit" class="btn btn-sm bg-gradient-primary-to-secondary py-1 text-light" value="Afficher">
                                        </div>
                                    </div>
                                </form>
@endif

                                <!-- Add panel button -->
                                <p class="mt-sm-0 mt-2 mb-0">
@if (Route::is('dashboard.home.datas'))
                                    <a href="{{ route('dashboard.home') }}" class="btn btn-secondary btn-sm pb-sm-1 me-1 float-end text-white">
                                        <i class="bi bi-chevron-double-left me-2"></i>Retour
                                    </a>
@else
                                    <button class="btn btn-sm bg-gradient-primary-to-secondary px-4 pb-sm-1 text-white" data-bs-toggle="modal" data-bs-target="#panelModal">Ajouter un panneau</button>
@endif
                                </p>
                            </div>

@if (!empty($selected_item))
    @include('partials.home.datas')
@else
    @include('partials.home.home')
@endif
                        </div>
                    </div>
                </div>
            </section>

@endsection
