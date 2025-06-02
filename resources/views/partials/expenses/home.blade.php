
                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped-columns table-bordered border-top">
                                        <thead>
                                            <tr>
                                                <th>Motif de dépense</th>
                                                <th>Montant (en USD)</th>
                                                <th>Date de sortie</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($expenses as $expense)
                                            <tr>
                                                <td class="align-middle">{{ $expense['designation'] }}</td>
                                                <td class="align-middle text-center">{{ $expense['amount'] }}</td>
                                                <td class="align-middle">{{ $expense['outflow_date'] }}</td>
                                                <td class="align-middle">
                                                    <a class="text-decoration-none" href="{{ route('dashboard.expense.datas', ['id' => $expense['id']]) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a><br>
                                                    <a href="{{ route('dashboard.expense.delete', ['id' => $expense['id']]) }}" class="text-decoration-none text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </td>
                                            </tr>
@empty
                                            <tr>
                                                <td colspan="4" class="lead text-center">La liste est encore vide</td>
                                            </tr>
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            {{ $expenses_req->links() }}
                        </div>
                    </div>
