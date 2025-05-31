
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <div class="table-responsive mb-3">
                                    <table class="table table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Noms</th>
                                                <th>Téléphone</th>
                                                <th>Rôle</th>
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
                                                <td class="align-middle">
                                                    <a class="text-decoration-none" href="{{ route('dashboard.user.datas', ['id' => $user['id']]) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a><br>
                                                    <a href="{{ route('dashboard.user.delete', ['id' => $user['id']]) }}" class="text-decoration-none text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </td>
                                            </tr>
@empty
                                            <tr>
                                                <td colspan="5" class="lead text-center">La liste est encore vide</td>
                                            </tr>
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            {{ $users_req->links() }}
                        </div>
                    </div>
