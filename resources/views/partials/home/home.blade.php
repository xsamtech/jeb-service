
                            <!-- Table title -->
                            <div class="row mb-4">
                                <div class="col-lg-6 col-sm-8 col-12 mx-auto">
@php
    // Créer un objet Carbon pour la date avec le mois et l'année donnés
    $date = Carbon\Carbon::create($year, $month, 1);
@endphp
                                    <h4 class="m-0 text-center">Locations pour {{ $monthName }} {{ $year }}</h4>
                                </div>
                            </div>

                            <!-- Table header -->
                            <div id="tableHeader" class="card card-body p-0 d-sm-block d-none border-bottom-0 rounded-0 text-center">
                                <div class="row g-0">
                                    <div class="col-sm-4">
                                        <div class="row g-0">
                                            <div class="col-sm-7">
                                                <div class="card card-body h-100 border-0 rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Site / Emplacement
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Taxe d’implantation
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row g-0">
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Faces
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Taxe d’affichage
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Prix de location
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Autres dépenses
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    Montant restant
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table body -->
@forelse ($panelsData as $panel)
                            <div id="tableBody" class="card card-body mb-sm-0 mb-3 p-0 @if (!$loop->first) border-top-0 @endif rounded-0 text-center">
                                <div class="row g-0">
                                    <div class="col-sm-4">
                                        <div class="row g-0">
                                            <!-- Nom du panneau -->
                                            <div class="col-sm-7">
                                                <div class="card card-body h-100 border-0 rounded-0" style="background-color: rgba(300,300,300,0.07);">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <p class="m-0">{{ $panel['panel'] }}</p>

                                                        <div class="dropdown">
                                                            <a role="button" class="btn btn-link p-0" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </a>

                                                            <ul class="dropdown-menu bg-dark py-0">
                                                                <li><a class="dropdown-item py-2" href="{{ route('dashboard.home.datas', ['entity' => 'panel', 'id' => $panel['id']]) }}">Modifier</a></li>
                                                                <li><a role="button" class="dropdown-item py-2" onclick="event.preventDefault(); performAction('delete', 'panel', 'item-{{ $panel['id'] }}')">Supprimer</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Taxe d’implantation -->
                                            <div class="col-sm-5">
                                                <div class="card card-body rounded-0 panel-column taxe-implantation">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Taxe d’implantation</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatDecimalNumber($panel['taxe_implantation']) }} $</span>
    @if (!$tithePaid)
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
    @endif
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="{{ route('expenses.store.taxe_implantation') }}" method="POST">
    @csrf
                                                            <input type="hidden" name="panel_id" value="{{ $panel['id'] }}">
                                                            <input type="hidden" name="year" value="{{ request()->get('year', now()->year) }}">
                                                            <input type="hidden" name="month" value="{{ request()->get('month', now()->month) }}">

                                                            <label for="amount_taxe_implantation_{{ $loop->index }}" class="form-label m-0">Montant</label>
                                                            <input type="number" name="amount" step="0.01" id="amount_taxe_implantation_{{ $loop->index }}" class="form-control">

                                                            <label for="outflow_date_taxe_implantation_{{ $loop->index }}" class="form-label mt-2 mb-0">Date de sortie</label>
                                                            <input type="datetime" name="outflow_date" id="outflow_date_taxe_implantation_{{ $loop->index }}" class="form-control">

                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>
                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
    @forelse ($panel['expenses'] as $face)
                                        <div class="row g-0">
                                            <!-- Nom de la face -->
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column" style="background-color: rgba(300,300,300,0.07);">
                                                    <p class="m-0"><strong>{{ $face['face_name'] }}</strong></p>
                                                </div>
                                            </div>

                                            <!-- Taxe d’affichage -->
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Taxe d’affichage</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatDecimalNumber($face['taxe_affichage']) }} $</span>
        @if (!$tithePaid)
            @if($face['is_available'])
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
            @endif
        @endif
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="{{ route('expenses.store.taxe_affichage') }}" method="POST">
        @csrf
                                                            <input type="hidden" name="rented_face_id" value="{{ !empty($face['rented_face_id']) ? $face['rented_face_id'] : $face['face_id'] }}">
                                                            <input type="hidden" name="year" value="{{ request()->get('year', now()->year) }}">
                                                            <input type="hidden" name="month" value="{{ request()->get('month', now()->month) }}">

                                                            <label for="amount_taxe_affichage_{{ $loop->index }}" class="form-label m-0">Montant</label>
                                                            <input type="number" name="amount" step="0.01" id="amount_taxe_affichage_{{ $loop->index }}" class="form-control">

                                                            <label for="outflow_date_taxe_affichage_{{ $loop->index }}" class="form-label mt-2 mb-0">Date de sortie</label>
                                                            <input type="datetime" name="outflow_date" id="outflow_date_taxe_affichage_{{ $loop->index }}" class="form-control">

                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>
                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Prix de location</span>

                                                    <!-- Affichage simple -->
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="me-2">
                                                            {{ formatDecimalNumber($face['face_price']) . ' $' }}
                                                        </span>

        @if (!$tithePaid)
            @if($face['taxe_affichage'] > 0)
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
            @endif
        @endif
                                                    </div>

                                                    <!-- Formulaire de modification -->
                                                    <div class="update-data d-none">
                                                        <form action="{{ route('rented_faces.update_price') }}" method="POST">
        @csrf
                                                            <input type="hidden" name="rented_face_id" value="{{ $face['rented_face_id'] }}">

                                                            <input type="number" name="price" step="0.01" value="{{ $face['face_price'] }}" class="form-control">
                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>

                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view">
                                                                <i class="bi bi-x-lg"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Autres dépenses</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatDecimalNumber($face['total_other_expenses']) }} $</span>
        @if (!$tithePaid)
            @if ($face['face_price'] > 0)
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
            @endif
        @else
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-eye-fill"></i></a>
        @endif
                                                    </div>
                                                    <div class="update-data d-none">
        @foreach ($face['other_expenses'] as $expense)
                                                        <p class="mb-1">
                                                            <strong>{{ $expense['designation'] }}</strong><br>
                                                            {{ formatDecimalNumber($expense['amount']) . ' $' }}
                                                        </p>
        @endforeach

        @if (!$tithePaid)
                                                        <form action="{{ route('expenses.store.other_expense') }}" method="POST">
            @csrf
                                                            <input type="hidden" name="rented_face_id" value="{{ $face['rented_face_id'] }}">

                                                            <label for="designation_other_expense_{{ $loop->index }}" class="form-label m-0">Designation</label>
                                                            <input type="text" name="designation" id="designation_other_expense_{{ $loop->index }}" class="form-control mb-2" placeholder="Designation">

                                                            <label for="amount_other_expense_{{ $loop->index }}" class="form-label m-0">Montant</label>
                                                            <input type="number" name="amount" step="0.01" id="amount_other_expense_{{ $loop->index }}" class="form-control">

                                                            <label for="outflow_date_other_expense_{{ $loop->index }}" class="form-label mt-2 mb-0">Date de sortie</label>
                                                            <input type="datetime" name="outflow_date" id="outflow_date_other_expense_{{ $loop->index }}" class="form-control">

                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 rounded-pill text-white">Enregistrer</button>
                                                        </form>
        @endif
                                                        <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Montant restant</span>
                                                    <strong>{{ formatDecimalNumber($face['remaining_amount']) . ' $' }}</strong>
                                                </div>
                                            </div>
                                        </div>
        
    @empty
                                        <div class="row g-0">
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 panel-column">
                                                    <span class="text-muted fst-italic">Il n'y a aucune face pour ce panneau</span>
                                                </div>
                                            </div>
                                        </div>
    @endforelse
                                    </div>
                                </div>
                            </div>
@empty
                            <div class="card card-body rounded-0 text-center">
                                <span class="text-muted fst-italic">Il n'y a encore aucun panneau enregistré</span>
                            </div>
@endforelse

@if (count($panelsData) > 0)
                            <div class="row mt-4">
                                <div class="col-lg-4 col-sm-6 col-12 ms-auto">
                                    <div class="card p-0 rounded-0">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <p class="m-0">Dépenses du mois</p>
    @if ($totalRemaining > 0)
        @if (!$tithePaid)
                                            <a role="button" class="btn btn-link p-0 switch-month-expense"><i class="bi bi-plus-lg"></i></a>
        @endif
    @endif
                                        </div>

                                        <ul class="list-group list-group-flush border-bottom-0 month-expenses">
    @forelse ($monthExpenses as $expense)
                                            <li class="list-group-item small bg-transparent">{{ $expense->designation . ' : ' . formatDecimalNumber($expense->amount) . ' $' }}</li>
    @empty
                                            <li class="list-group-item small bg-transparent py-3">Il n'y a aucune dépense pour ce mois</li>
    @endforelse
                                        </ul>

                                        <div class="card-body update-month-expense d-none">
                                            <form action="{{ route('expenses.store.other_expense') }}" method="POST">
    @csrf
                                                <input type="hidden" name="month_data_id" value="{{ $monthDataID }}">
                                                <input type="hidden" name="remaining_amount" value="{{ $monthDataID }}">

                                                <label for="designation_month_data" class="form-label m-0">Designation</label>
                                                <input type="text" name="designation" id="designation_month_data" class="form-control mb-2" placeholder="Designation">

                                                <label for="amount_month_data" class="form-label m-0">Montant</label>
                                                <input type="number" name="amount" step="0.01" id="amount_month_data" class="form-control">

                                                <label for="outflow_date" class="form-label mt-2 mb-0">Date de sortie</label>
                                                <input type="datetime" name="outflow_date" id="outflow_date_month_data" class="form-control">

                                                <button class="btn btn-sm bg-gradient-primary-to-secondary mt-2 px-3 pb-1 rounded-pill text-white">Enregistrer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12 mt-sm-0 mt-4 me-auto">
                                    <div class="card card-body p-0 border-0 rounded-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered m-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-uppercase">Taxes d'implantation du mois</td>
                                                        <td>{{ formatDecimalNumber($totalTaxeImplantation) }} $</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-uppercase">Reste du mois</td>
                                                        <td>{{ formatDecimalNumber($totalRemaining) }} $</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-uppercase" colspan="2">
                                                            <div class="d-flex justify-content-between">
                                                                <div>
                                                                    Dîme : {{ formatDecimalNumber($tithe) }} $
                                                                </div>
                                                                <div>
                                                                    <div class="form-check form-switch">
    @if ($tithePaid)
                                                                        <label class="form-check-label text-success" for="item-{{ !$monthDataID ? 0 : $monthDataID }}">Payée</label>
    @else
                                                                        <label class="form-check-label text-danger" for="item-{{ !$monthDataID ? 0 : $monthDataID }}">Non payée</label>
    @endif
                                                                        <input class="form-check-input" type="checkbox" role="switch" id="item-{{ !$monthDataID ? 0 : $monthDataID }}" data-totalremaining="{{ $totalRemaining }}" data-month="{{ $month }}" data-year="{{ $year }}" onclick="event.preventDefault(); performAction('change', 'tithe_paid', 'item-{{ !$monthDataID ? 0 : $monthDataID }}')">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
@endif

                            {{-- <!-- Table footer -->
                            <div id="tableFooter" class="card card-body rounded-0 pb-0 border-0">
                                {{ $panels_req->links() }}
                            </div> --}}
