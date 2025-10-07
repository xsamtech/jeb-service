
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
                                                    Date limite de location
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
                                            <div class="col-sm-7">
                                                <div class="card card-body h-100 border-0 rounded-0 text-start" style="background-color: rgba(300,300,300,0.07);">
                                                    <a href="{{ route('dashboard.home.datas', ['entity' => 'panel', 'id' => $panel['id']]) }}" class="text-decoration-none">
                                                        {{ $panel['panel'] }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="card card-body rounded-0 panel-column taxe-implantation">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Taxe d’implantation</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatIntegerNumber($panel['taxe_implantation']) }} $</span>
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="#" method="POST">
    @csrf
                                                            <input type="hidden" name="panel_id" value="{{ $panel['id'] }}">
                                                            <input type="number" name="expense_taxe_implantation" id="expense_taxe_implantation" class="form-control">
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
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column" style="background-color: rgba(300,300,300,0.07);">
                                                    {{ $face['face_name'] }}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Taxe d’affichage</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatIntegerNumber($face['taxe_affichage']) }} $</span>
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="#" method="POST">
        @csrf
                                                            <input type="hidden" name="customer_order_id" value="{{ $face['customer_order_id'] }}">
                                                            <input type="number" name="expense_taxe_affichage" id="expense_taxe_affichage" class="form-control">
                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>
                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Date limite de location</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ $face['date_limite_location'] }}</span>
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="#" method="POST">
        @csrf
                                                            <input type="hidden" name="customer_order_id" value="{{ $face['customer_order_id'] }}">
                                                            <input type="text" name="expense_end_date" id="end_date" class="form-control">
                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>
                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Autres dépenses</span>
                                                    <div class="d-flex justify-content-between show-data">
                                                        <span class="d-sm-inline-block d-block me-2">{{ formatIntegerNumber($face['total_other_expenses']) }} $</span>
                                                        <a role="button" class="btn btn-link p-0 switch-view"><i class="bi bi-pencil"></i></a>
                                                    </div>
                                                    <div class="update-data d-none">
                                                        <form action="#" method="POST">
        @foreach ($face['other_expenses'] as $expense)
                                                            <p class="mb-1">{{ $expense['designation'] . ' : ' . formatIntegerNumber($expense['amount']) . ' $' }}</p>
        @endforeach

        @csrf
                                                            <input type="hidden" name="customer_order_id" value="{{ $face['customer_order_id'] }}">
                                                            <input type="number" name="expense_designation" id="expense_designation" class="form-control">
                                                            <input type="number" name="expense_amount" id="expense_amount" class="form-control">
                                                            <button class="btn btn-sm bg-gradient-primary-to-secondary mt-1 me-1 pb-1 w-75 rounded-pill text-white">Enregistrer</button>
                                                            <a role="button" class="btn btn-sm btn-danger mt-1 pb-1 px-1 rounded-pill switch-view"><i class="bi bi-x-lg"></i></a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">
                                                    <span class="d-sm-none d-inline-block me-2 mb-2 text-decoration-underline">Montant restant</span>
                                                    <strong>{{ formatIntegerNumber($face['remaining_amount']) . ' $' }}</strong>
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

                            {{-- <!-- Table footer -->
                            <div id="tableFooter" class="card card-body rounded-0 pb-0 border-0">
                                {{ $panels_req->links() }}
                            </div> --}}
