                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="m-0 table table-striped table-bordered border-top">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Noms</th>
                                                <th>Téléphone</th>
                                                <th>Panneaux loués</th>
                                                <th>Totaux locations</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($carts as $cart)
    @php
        $first_order = $cart['orders'][0];
    @endphp
                                            <tr>
                                                <td class="py-3">
                                                    <!-- Affichage de l'avatar de l'utilisateur -->
                                                    <img src="{{ $first_order->user->avatar_url ?? asset('assets/img/user.png') }}" alt="Avatar" class="img-fluid" width="50" height="50" style="border-radius: 50%;">
                                                </td>
                                                <td class="py-3">
                                                    <!-- Nom complet de l'utilisateur -->
                                                    {{ $first_order->user->firstname }} {{ $first_order->user->lastname }}
                                                </td>
                                                <td class="py-3">
                                                    <!-- Téléphone de l'utilisateur -->
                                                    {{ $first_order->user->phone }}
                                                </td>
                                                <td class="py-3">
                                                    <p class="mb-2 text-decoration-underline">Loué(s) le {{ explicitDate($cart['created_at']) }}</p>
                                                    <ul class="ps-3">
    @foreach ($cart['orders'] as $order)
        @php
            $date1 = new Carbon\Carbon($order['created_at']);
            $date2 = new Carbon\Carbon($order['end_date']);
            $duration = $date1->diff($date2);
            // Calculate total price
            $count_duration = ($duration->d == 0 ? 1 : $duration->d);
            $subtotal_price = $order['price_at_that_time'] * $count_duration;
        @endphp
                                                        <li class="mb-1" style="max-width: 160px; min-width: 100px; line-height: 20px;">
                                                            {{ $order['face']['panel']['location'] }} (<strong>{{ strtoupper($order['face']['face_name']) }}</strong>)

                                                            <div class="card card-body mt-2 px-2 py-1 bg-transparent border" style="border-color: #ccc">
                                                                <p class="m-0 text-primary">{{ formatDecimalNumber($order['face']['panel']['price']) }}$ <i class="bi bi-x-lg"></i> {{ $count_duration . ' jour' . ($count_duration > 1 ? 's' : '') }}</p>
                                                                <p class="mt-1 mb-0">Sous-total : <strong>{{ formatDecimalNumber($subtotal_price) }}$</strong></p>
                                                                <p class="mt-1 mb-0">Dîme (10%) : <strong>{{ formatDecimalNumber($order['tithe']) }}$</strong></p>
                                                            </div>
                                                        </li>
    @endforeach
                                                    </ul>
                                                </td>
                                                <td class="py-3">
                                                    <p class="mb-1"><u>Total loué</u></p>
                                                    <strong>{{ $cart['total_amount'] }}</strong>

                                                    <p class="mt-3 mb-1"><u>Dépenses</u></p>
                                                    <ul class="mb-2 ps-3">
                                                        <li>
                                                            Pour dîmes :<br> {{ $cart['tithe_10_percent_expenses_total'] }}
                                                        </li>
                                                        <li>
                                                            Pour autres :<br> {{ $cart['other_expenses_total'] }}
                                                        </li>
                                                    </ul>
                                                    <hr class="mt-0 mb-1">
                                                    Total : <strong>{{ $cart['all_expenses_total'] }}</strong>

                                                    <p class="mt-3 mb-1"><u>Reste à la caisse</u></p>
                                                    <strong>{{ $cart['remaining_amount'] }}</strong>
                                                </td>
                                                <td class="py-3">
                                                    <a class="btn btn-sm w-100 btn-info py-0 rounded-pill" href="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}">
                                                        Détails<i class="bi bi-chevron-double-right ms-1"></i>
                                                    </a>
    @if ($cart['is_paid'] == 0)
                                                    <a role="button" class="btn btn-sm w-100 btn-danger mt-1 py-0 rounded-pill" onclick="event.preventDefault(); performAction('delete', 'cart', 'item-{{ $cart['id'] }}')">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
    @endif
    @if ($cart['is_paid'] == 0)
                                                    <form action="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}" method="post">
        @csrf
                                                        <input type="hidden" name="is_paid" value="1">
                                                        <button class="btn btn-sm w-100 btn-outline-success mt-1 pt-0 pb-1 rounded-pill">
                                                            Attester paiement
                                                        </button>
                                                    </form>
    @else
                                                    <form action="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}" method="post">
        @csrf
                                                        <input type="hidden" name="is_paid" value="0">
                                                        <button class="btn btn-sm w-100 btn-outline-danger mt-1 pt-0 pb-1 rounded-pill">
                                                            Annuler paiement
                                                        </button>
                                                    </form>
    @endif
                                                </td>
                                            </tr>
@empty
                                            <tr>
                                                <td colspan="6" class="lead text-center">La liste est encore vide</td>
                                            </tr>
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            {{ $carts_req->links() }}
                        </div>
                    </div>
