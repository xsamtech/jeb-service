
                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Find by is_available -->
                                <form method="GET" class="form-search mx-auto mb-3">
                                    <div class="row g-1">
                                        <div class="col-auto">
                                            <select name="is_available" id="is_available" class="form-select form-select-sm">
                                                <option class="small" disabled{{ !request()->has('is_available') ? ' selected' : '' }}>Choisir un état</option>
                                                <option value="1"{{ request()->get('is_available') == '1' ? ' selected' : '' }}>Disponible</option>
                                                <option value="0"{{ request()->get('is_available') == '0' ? ' selected' : '' }}>Indisponible</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <input type="submit" class="btn btn-sm btn-dark py-1" value="Trier">
                                        </div>
@if (request()->has('is_available'))
                                        <div class="col-auto">
                                            <a href="{{ route('dashboard.panels') }}" class="btn btn-sm btn-link py-1">Voir tous les panneaux</a>
                                        </div>
@endif
                                    </div>
                                </form>

                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-bordered border-top">
                                        <thead>
                                            <tr class="bg-light">
                                                <th style="max-width: 12rem;">Site / Emplacement</th>
                                                <th>Dimension</th>
                                                <th>Format</th>
                                                <th>Prix unitaire</th>
                                                <th>Quantité en stock</th>
@if (!request()->has('is_available'))
                                                <th>Est disponible</th>
@endif
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($panels as $panel)
                                            <tr>
                                                <td class="align-middle" style="max-width: 12rem; background-color: #bdf">{{ $panel['location'] }}</td>
                                                <td class="align-middle text-center">{{ $panel['dimensions'] }}</td>
                                                <td class="align-middle text-center">{{ $panel['format'] }}</td>
                                                <td class="align-middle text-center">{{ $panel['unit_price'] }}</td>
                                                <td class="align-middle text-center">{{ $panel['quantity'] }}</td>
    @if (!request()->has('is_available'))
                                                <td class="align-middle text-center">
                                                    <h6>
                                                        <div class="badge text-bg-{{ $panel['is_available'] == 1 ? 'success' : 'danger' }} fw-normal">
                                                            {{ $panel['is_available'] == 1 ? 'Oui' : 'Non' }}
                                                        </div>
                                                    </h6>
                                                </td>
    @endif
                                                <td class="align-middle">
                                                    <a class="text-decoration-none" href="{{ route('dashboard.panel.datas', ['id' => $panel['id']]) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a><br>
                                                    <a href="{{ route('dashboard.panel.delete', ['id' => $panel['id']]) }}" class="text-decoration-none text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </td>
                                            </tr>
@empty
                                            <tr>
                                                <td colspan="{{ request()->has('is_available') ? 6 : 7 }}" class="lead text-center">La liste est encore vide</td>
                                            </tr>
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            {{ $panels_req->links() }}
                        </div>
                    </div>
