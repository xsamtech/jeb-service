
                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped border-top">
                                        <thead>
                                            <tr>
                                                <th>Nom du rôle</th>
                                                <th>Description</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($roles as $role)
                                            <tr>
                                                <td class="align-middle">{{ $role->role_name }}</td>
                                                <td class="align-middle">{{ $role->role_description }}</td>
                                                <td class="align-middle">
                                                    <a class="text-decoration-none" href="{{ route('dashboard.user.entity.datas', ['entity' => 'roles', 'id' => $role->id]) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a><br>
                                                    <a href="{{ route('dashboard.user.entity.delete', ['entity' => 'roles', 'id' => $role->id]) }}" class="text-decoration-none text-danger">
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
                    </div>

