                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
{{-- <pre>{{ print_r($users[0]['unpaid_cart'], true) }}</pre> --}}
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered border-top">
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
@forelse ($users as $user)
    @php
        $cart = $user['unpaid_cart']?->resolve();
    @endphp
                                            <tr>
                                                <td class="align-middle">
                                                    <img src="{{ $user['avatar_url'] }}" alt="{{ $user['firstname'] . ' ' . $user['lastname'] }}" width="50" class="ms-sm-2 rounded-circle">
                                                </td>
                                                <td class="align-middle">{{ $user['firstname'] . ' ' . $user['lastname'] }}</td>
                                                <td class="align-middle">{{ $user['phone'] }}</td>
                                                <td class="align-middle">

    @if (!empty($cart) && !empty($cart['orders']))
                                                    <ul class="mb-0">
        @foreach ($cart['orders'] as $order)
                                                        <li>
                                                            {{ $order['panel']['location'] ?? 'Panneau inconnu' }}

            @if (count($order['expenses']) > 1)
                                                            <ul class="mb-3">
                @foreach ($order['expenses'] as $expense)
                                                                <li>
                                                                    <u>{{ $expense['designation'] }}</u> : <strong>{{ formatDecimalNumber($expense['amount']) }}$</strong>
                                                                </li>
                @endforeach
                                                            </ul>
            @else
                                                            <p class="mb-3"><u>{{ $order['expenses'][0]['designation'] }}</u> : <strong>{{ formatDecimalNumber($order['expenses'][0]['amount']) }}$</strong></p>
            @endif

                                                        </li>
        @endforeach
                                                    </ul>
    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <p class="mb-2">
                                                        <u>Total des prix</u><br/> <strong>{{ $cart['total_amount'] }}</strong>
                                                    </p>
                                                    <p class="m-0">
                                                        <u>Dépenses</u>
                                                        <ul class="ps-2 m-0">
                                                            <li>Pour dîmes : {{ $cart['tithe_10_percent_expenses_total'] }}</li>
                                                            <li>Pour autres : {{ $cart['other_expenses_total'] }}</li>
                                                        </ul>
                                                        <hr class="m-0">
                                                        <p class="m-0 text-center">Total : <strong>{{ $cart['all_expenses_total'] }}</strong></p>
                                                    </p>
                                                    <p class="mb-2">
                                                        <u>Reste à la caisse</u><br/> <strong>{{ $cart['remaining_amount'] }}</strong>
                                                    </p>
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-sm w-100 btn-info py-0 rounded-pill" href="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}">
                                                        Détails<i class="bi bi-chevron-double-right ms-1"></i>
                                                    </a>
                                                    <a class="btn btn-sm w-100 btn-danger mt-1 py-0 rounded-pill" href="{{ route('dashboard.user.entity.delete', ['entity' => 'cart', 'id' => $cart['id']]) }}">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
    @if ($cart['is_paid'] == 0)
                                                    <form action="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}" method="post">
        @csrf
                                                        <input type="hidden" name="is_paid" value="1">
                                                        <button class="btn btn-sm w-100 btn-success mt-1 py-0 rounded-pill">
                                                            Attester paiement
                                                        </button>
                                                    </form>
    @else
                                                    <form action="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}" method="post">
        @csrf
                                                        <input type="hidden" name="is_paid" value="0">
                                                        <button class="btn btn-sm w-100 btn-danger mt-1 py-0 rounded-pill">
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
                            {{ $users_req->links() }}
                        </div>
                    </div>
