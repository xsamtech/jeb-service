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
                                                <th></th>
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
                                                    {{-- <form class="row row-cols-sm-auto g-0 align-items-center" method="POST" action="{{ route('dashboard.user.entity.datas', ['entity' => 'orders', 'id' => $order->id]) }}">
                                                        <div class="col-12">
                                                            <input type="text" name="end_date" id="end_date{{ $order->id }}" class="form-control rounded-0" value="{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->end_date)->format('d/m/Y H:i') }}">
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="submit" class="btn bg-gradient-primary-to-secondary rounded-0" style="width: 37px; height: 37px; padding: 0;"><i class="bi bi-check text-white fs-3" style="position: relative;; top: -3px;"></i></button>
                                                        </div>
                                                    </form> --}}
                                                </td>
                                                <td class="align-middle">
    @php
        $count_duration = ($duration->d == 0 ? 1 : $duration->d);
        $total_price = $order->price_at_that_time * $count_duration;
    @endphp
                                                    {{ formatDecimalNumber($total_price) . ' $' }}
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-sm w-100 btn-danger py-0 rounded-pill" href="{{ route('dashboard.user.entity.delete', ['entity' => 'orders', 'id' => $order->id]) }}">
                                                        <i class="bi bi-trash me-2"></i>Retirer
                                                    </a>
                                                </td>
                                            </tr>
@empty
@endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
