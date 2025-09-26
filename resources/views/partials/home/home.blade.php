
                            <!-- Table header -->
                            <div id="tableHeader" class="card card-body p-0 d-sm-block d-none border-bottom-0 rounded-0 text-center">
                                <div class="row g-0">
                                    <div class="col-sm-4">
                                        <div class="row g-0">
                                            <div class="col-sm-7">
                                                <div class="card card-body h-100 border-0 rounded-0">
                                                    Site / Emplacement
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Taxe d’implantation
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row g-0">
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Faces
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Taxe d’affichage
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Date limite de location
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Dépenses
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 border-0 border-start rounded-0">
                                                    Montant restant
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table body -->
@forelse ($panels as $panel)
                            <div id="tableBody" class="card card-body mb-sm-0 mb-3 p-0 @if (!$loop->first) border-top-0 @endif rounded-0 text-center">
                                <div class="row g-0">
                                    <div class="col-sm-4">
                                        <div class="row g-0">
                                            <div class="col-sm-7">
                                                <div class="card card-body h-100 border-0 rounded-0 text-start" style="background-color: rgba(300,300,300,0.07);">
                                                    {{ $panel['location'] }}
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="card card-body h-100 rounded-0 panel-column">
test{{ $loop->index }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
    @forelse ($panel['faces'] as $face)
                                        <div class="row g-0">
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column" style="background-color: rgba(300,300,300,0.07);">
                                                    {{ $face['face_name'] }}
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">

                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card card-body h-100 rounded-0 face-column">

                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">

                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="card card-body h-100 rounded-0 face-column">

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

                            <!-- Table footer -->
                            <div id="tableFooter" class="card card-body rounded-0 pb-0 border-0">
                                {{ $panels_req->links() }}
                            </div>
