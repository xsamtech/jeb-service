
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

                                        <!-- Add order -->
@if ($selected_expense->customer_order_id != null)
                                        <div id="selectedOrder" class="col-12 d-none">
                                            <div class="card card-body">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <input type="hidden" name="customer_order_id" id="order_id">
                                                    <a role="button" id="openOrderModal" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ordersListModal2">
                                                        <i class="bi bi-trash me-2"></i>Changer de location
                                                    </a>
                                                    <a role="button" class="btn btn-sm btn-danger ms-sm-2 py-0 rounded-pill" onclick="event.preventDefault(); performAction('delete', 'expense_order', 'item-{{ $expense_order->id }}')">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                </div>
                                                <p class="card-text small"><u>Panneau</u> :<br><strong id="location">{{ $expense_order->face->panel->location . ' (' . strtolower($expense_order->face->face_name) . ')' }}</strong></p>
                                                <p class="card-text small"><u>Date de commande</u> :<br><strong>Le</strong> <strong id="created_at">{{ $expense_order->created_at_explicit }}</strong></p>
                                                <p class="card-text small"><u>Loué par</u> :<br><strong id="user_fullname">{{ $expense_order->user->firstname . ' ' . $expense_order->user->lastname }}</strong></p>
                                            </div>
                                        </div>
@endif
                                    </div>

                                    <button type="submit" class="btn bg-gradient-primary-to-secondary w-100 mt-3 rounded-pill text-white">@lang('miscellaneous.register')</button>
                                </form>

                            </div>
                        </div>
                    </div>

