
                    <div class="row">
                        <div class="col-lg-6 col-md-7 mx-auto">
                            <div class="card card-body border">
                                <h4 class="card-title mb-0 text-gradient text-center fw-bold">Modifier la dépense</h4>

                                <!-- Find by is_available -->
                                <form method="POST" action="{{ route('dashboard.expense.datas', ['id' => $selected_expense->id]) }}" class="mx-auto mt-3">
@csrf
                                    <div class="row g-3">
                                        <!-- Expense reason (designation) -->
                                        <div class="col-sm-6">
                                            <label for="designation" class="form-label fw-bold">Motif de dépense</label>
                                            <input type="text" name="designation" class="form-control" id="designation" value="{{ $selected_expense->designation }}">
                                        </div>

                                        <!-- Amount -->
                                        <div class="col-sm-6">
                                            <label for="amount" class="form-label fw-bold">Montant (en $)</label>
                                            <input type="number" step="0.01" name="amount" class="form-control" id="amount" value="{{ $selected_expense->amount }}">
                                        </div>

                                        <!-- Outflow date -->
                                        <div class="col-sm-6">
                                            <label for="outflow_date" class="form-label fw-bold">Date/Heure de sortie</label>
                                            <input type="datetime" name="outflow_date" class="form-control" id="outflow_date" value="{{ !empty($selected_expense->outflow_date) ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selected_expense->outflow_date)->format('d/m/Y H:i') : null }}">
                                        </div>
{{-- 
                                        <!-- Add order -->
                                        <div class="col-sm-6">
                                            <input type="hidden" name="customer_order_id" id="order_id">
                                            <label class="form-label fw-bold">Associer à une location</label>
                                            <a role="button" id="openOrderModal" class="btn btn-sm btn-light border w-100" data-bs-toggle="modal" data-bs-target="#ordersListModal">Voir la liste</a>
                                        </div>

                                        <div id="selectedOrder" class="col-12 d-none">
                                            <div class="card card-body">
                                                <p class="card-text small"><u>Panneau</u> :<br><strong id="location"></strong></p>
                                                <p class="card-text small"><u>Date de commande</u> :<br><strong>Le</strong> <strong id="created_at"></strong></p>
                                                <p class="card-text small"><u>Loué par</u> :<br><strong id="user_fullname"></strong></p>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <button type="submit" class="btn bg-gradient-primary-to-secondary w-100 mt-3 rounded-pill text-white">@lang('miscellaneous.register')</button>
                                </form>

                            </div>
                        </div>
                    </div>

