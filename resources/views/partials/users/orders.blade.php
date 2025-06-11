                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
{{-- <pre>{{ print_r($users[0]['unpaid_cart'], true) }}</pre> --}}
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped border-top">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Noms</th>
                                                <th>Téléphone</th>
                                                <th>Locations</th>
                                                <th>Dépenses sur la location</th>
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
    @php
        $cart = $user['unpaid_cart']?->resolve();
    @endphp

    @if (!empty($cart) && !empty($cart['orders']))
                                                    <ul class="mb-0">
        @foreach ($cart['orders'] as $index => $order)
            @if (count($cart['orders']) > 5 && $index >= 5)
                @break
            @endif
                                                        <li>{{ $order['panel']['location'] ?? 'Panneau inconnu' }}</li>
        @endforeach
        @if (count($cart['orders']) > 5)
                                                        <li>+{{ count($cart['orders']) - 5 }}</li>
        @endif
                                                    </ul>
    @endif
                                                </td>
                                                <td class="align-middle">
    @if (!empty($cart['orders']) && $cart['orders']->contains(function($order) { return $order['expenses']->isNotEmpty(); }))
                                                    <ul class="mb-0">
        @foreach ($cart['orders'] as $order)
                                                        <li>
            @if (count($order['expenses']) > 1)
                                                            <strong>{{ $order['panel']['location'] }}</strong>
                                                            <ul>
                @foreach ($order['expenses'] as $expense)
                                                                <li>
                                                                    <u>{{ $expense['designation'] }}</u><br>
                                                                    {{ $expense['amount'] }} $
                                                                </li>
                @endforeach
                                                            </ul>
            @else
                                                            <ul>
                @foreach ($order['expenses'] as $expense)
                                                                <li>
                                                                    <u>{{ $expense['designation'] }}</u><br>
                                                                    {{ $expense['amount'] }} $
                                                                </li>
                @endforeach
                                                            </ul>
            @endif
                                                        </li>
        @endforeach
                                                    </ul>
    @else
                                                    <i>Aucune dépense pour cette location</i>
    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <a class="text-decoration-none" href="{{ route('dashboard.user.datas', ['id' => $user['id']]) }}">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a><br>
                                                    <a href="{{ route('dashboard.user.entity.delete', ['entity' => 'cart', 'id' => $cart['id']]) }}" class="text-decoration-none text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </a>
                                                    <form action="{{ route('dashboard.user.entity.datas', ['entity' => 'cart', 'id' => $cart['id']]) }}" method="post">
    @csrf
                                                        <input type="hidden" name="is_paid" value="1">
                                                        <button class="btn btn-sm btn-link p-0 text-decoration-none">
                                                            <i class="bi bi-cash me-2"></i>Payer
                                                        </button>
                                                    </form>
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
