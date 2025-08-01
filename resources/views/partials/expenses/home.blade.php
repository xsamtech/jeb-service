
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
                                                    <a class="btn btn-sm btn-info py-0 rounded-pill" href="{{ route('dashboard.expense.datas', ['id' => $expense['id']]) }}">
                                                        Détails<i class="bi bi-chevron-double-right ms-1"></i>
                                                    </a>
                                                    <a role="button" class="btn btn-sm btn-danger ms-sm-2 py-0 rounded-pill" onclick="event.preventDefault(); performAction('delete', 'expense', 'item-{{ $expense['id'] }}')">
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

@php
    $month = \Carbon\Carbon::now()->month;
    $year = \Carbon\Carbon::now()->year;
	$totalTithe = \App\Models\Expense::totalMonthlyTitheExpenses($month, $year);
@endphp

                            <p class="card-text my-3">Total des dîmes du mois : <strong>{{ formatDecimalNumber($totalTithe) }} $</strong></p>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            {{ $expenses_req->links() }}
                        </div>
                    </div>
