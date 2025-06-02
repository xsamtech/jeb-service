@extends('layouts.app', ['page_title' => Route::is('dashboard.expense.datas') ? 'Dépense du ' . explicitDate($expense->outflow_date) : 'Gérer les dépenses'])

@section('app-content')

            <!-- Title Section-->
            <section class="pb-3">
                <div class="container px-lg-5 px-sm-4">
                    <div class="d-flex justify-content-lg-between justify-content-center flex-lg-row flex-column align-items-center align-items-end mt-4">
                        <h1 class="fw-bolder py-lg-0 py-3 mb-0"><span class="text-gradient d-inline">{{ Route::is('dashboard.expense.datas') ? 'Dépense du ' . explicitDate($expense->outflow_date) : 'Gérer les dépenses' }}</span></h1>
                        <div class="flex-row text-center">
    @if (Route::is('dashboard.expense.datas'))
                            <a href="{{ route('dashboard.expenses') }}" class="btn btn-secondary btn-sm pb-sm-1 me-1 float-end text-white">
                                <i class="bi bi-chevron-double-left me-2"></i>Retour
                            </a>
    @else
                            <button class="btn btn-sm bg-gradient-primary-to-secondary px-4 pb-sm-1 text-white" data-bs-toggle="modal" data-bs-target="#expenseModal">Ajouter une dépense</button>
    @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Content Section-->
            <section class="pb-3">
                <div class="container-fluid container-lg px-lg-5">
    @if (Route::is('dashboard.expense.datas'))
        @include('partials.expenses.datas')
    @else
        @include('partials.expenses.home')
    @endif
                </div>
            </section>

@endsection
