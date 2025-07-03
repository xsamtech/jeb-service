
                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Find by status -->
                                <form method="GET" class="form-search mx-auto mb-3">
                                    <div class="row g-1">
                                        <div class="col-auto">
                                            <select name="status" id="status" class="form-select form-select-sm">
                                                <option class="small" disabled{{ !request()->has('status') ? ' selected' : '' }}>Choisir un état</option>
                                                <option value="1"{{ request()->get('status') == '1' ? ' selected' : '' }}>Activé</option>
                                                <option value="0"{{ request()->get('status') == '0' ? ' selected' : '' }}>Désactivé</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <input type="submit" class="btn btn-sm btn-dark py-1" value="Trier">
                                        </div>
@if (request()->has('status'))
                                        <div class="col-auto">
                                            <a href="{{ route('dashboard.users') }}" class="btn btn-sm btn-link py-1">Voir tout le monde</a>
                                        </div>
@endif
                                    </div>
                                </form>

                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped border-top">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Noms</th>
                                                <th>Téléphone</th>
                                                <th>Rôle</th>
@if (!request()->has('status'))
                                                <th>État</th>
@endif
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($users as $user)
                                            <tr>
                                                <td class="align-middle">
                                                    <img src="{{ $user['avatar_url'] }}" alt="{{ $user['firstname'] . ' ' . $user['lastname'] }}" width="50" class="ms-sm-2 rounded-circle">
                                                </td>
                                                <td class="align-middle">{{ $user['firstname'] . ' ' . $user['lastname'] }}</td>
                                                <td class="align-middle">{{ $user['phone'] }}</td>
                                                <td class="align-middle">
                                                    <select class="form-select form-select-sm" aria-label="Choisir un rôle" style="max-width: 10rem;">
                                                        <option class="small" disabled>Choisir un rôle</option>
    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"{{ $user['roles'][0]->id == $role->id ? ' selected' : '' }}>{{ $role->role_name }}</option>
    @endforeach
                                                    </select>
                                                </td>
    @if (!request()->has('status'))
                                                <td class="align-middle">
                                                    <h6>
                                                        <div class="badge text-bg-{{ $user['is_active'] == 1 ? 'success' : 'danger' }} fw-normal">
                                                            {{ $user['is_active'] == 1 ? 'Activé' : 'Désactivé' }}
                                                        </div>
                                                    </h6>
                                                </td>
    @endif
                                                <td class="align-middle">
                                                    <a class="btn btn-sm btn-info py-0 rounded-pill" href="{{ route('dashboard.user.datas', ['id' => $user['id']]) }}">
                                                        Détails<i class="bi bi-chevron-double-right ms-1"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-danger ms-sm-1 py-0 rounded-pill" href="{{ route('dashboard.user.delete', ['id' => $user['id']]) }}">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </td>
                                            </tr>
@empty
                                            <tr>
                                                <td colspan="{{ request()->has('status') ? 5 : 6 }}" class="lead text-center">La liste est encore vide</td>
                                            </tr>
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            {{ $users_req->links() }}
                        </div>
                    </div>
