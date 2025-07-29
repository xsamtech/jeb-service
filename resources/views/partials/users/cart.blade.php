                    <div id="dataList" class="row">
                        <div class="col-md-12">
                            <div class="card card-body border">
                                <!-- Data list content -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered border-top">
                                        <caption>{{ count($selected_cart['orders']) > 1 ? 'Panneaux loués' : 'Panneau loué' }} le {{ $selected_cart['created_at_explicit'] }}</caption>
                                        <thead>
                                            <tr>
                                                <th>Designation</th>
                                                <th>Prix unitaire</th>
                                                <th>Nombre de jours</th>
                                                <th>Prix total</th>
@if ($selected_cart['is_paid'] == 0)
                                                <th></th>
@endif
                                            </tr>
                                        </thead>

                                        <tbody>
@forelse ($selected_cart['orders'] as $order)
                                            <tr>
                                                <td class="align-middle">
                                                    {{ $order->face->panel->location }} (<strong>{{ strtoupper($order->face->face_name) }}</strong>) 
                                                </td>
                                                <td class="align-middle">
                                                    {{ formatDecimalNumber($order->price_at_that_time) . ' $' }}
                                                </td>
                                                <td class="align-middle">
    @php
        $date1 = new Carbon\Carbon($order->created_at);
        $date2 = new Carbon\Carbon($order->end_date);
        $duration = $date1->diff($date2);
    @endphp
                                                    {{ $duration->d . ' ' . ( $duration->d > 1 ? 'jours' : 'jour') }}
                                                </td>
                                                <td class="align-middle">
    @php
        $count_duration = ($duration->d == 0 ? 1 : $duration->d);
        $total_price = $order->price_at_that_time * $count_duration;
    @endphp
                                                    {{ formatDecimalNumber($total_price) . ' $' }}
                                                </td>
@if ($selected_cart['is_paid'] == 0)
                                                <td class="align-middle">
                                                    <a role="button" class="btn btn-sm w-100 btn-danger py-0 rounded-pill" onclick="event.preventDefault(); performAction('delete', 'order', 'item-{{ $order->id }}')">
                                                        <i class="bi bi-trash me-2"></i>Retirer
                                                    </a>
                                                </td>
@endif
                                            </tr>
@empty
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
