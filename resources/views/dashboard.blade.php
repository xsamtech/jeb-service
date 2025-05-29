@extends('layouts.app', ['page_title' => 'Tableau de bord'])

@section('app-content')

            <!-- Title Section-->
            <section class="pb-3">
                <div class="container px-sm-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Tableau de bord</span></h1>
                    </div>
                </div>
            </section>

            <!-- Widget Section-->
            <section class="pb-3">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-lg-4 col-sm-6 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-person-gear display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Utilisateur{{ count($users) > 1 ? 's' : '' }}</h3>
                                    <h5 class="text-white">{{ count($users) }}</h5>
                                </div>
                                <a href="{{ route('dashboard.users') }}" class="stretched-link"></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-piggy-bank display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Caisse</h3>
                                    <h5 class="text-white">{{ $balance_summary->balance . ' USD' }}</h5>
                                </div>
                                <a href="{{ route('dashboard.users') }}" class="stretched-link"></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-card-heading display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Panneau{{ count($panels) > 1 ? 'x' : '' }}</h3>
                                    <h5 class="text-white">{{ count($panels) }}</h5>
                                </div>
                                <a href="{{ route('dashboard.panels') }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Table Section-->
            <section class="pb-3">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="card card-body border">
                                <h3 class="card-title text-center fw-bold mb-3">Comptes</h3>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Total des actifs</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $total_orders }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Total des passifs</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $orders_paid }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Dîme (10%)</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $balance_summary->balance * 0.1 }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">TVA (16%)</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $balance_summary->balance * 0.16 }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Reste à la caisse</th>
                                                <td style="text-align: right; padding-right: 30px;">
                                                    {{ $balance_summary->balance - ($balance_summary->balance * 0.1) - ($balance_summary->balance * 0.16)  }} $
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title text-sm-start text-center">Rapports de la semaine</h5>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            new ApexCharts(document.querySelector("#reportsChart"), {
                                                series: [{
                                                    name: 'Commandes',
                                                    data: [31, 40, 28, 51, 42, 82, 56],
                                                }, {
                                                    name: 'Revenus',
                                                    data: [11, 32, 45, 32, 34, 52, 41]
                                                }, {
                                                    name: 'Panneaux',
                                                    data: [15, 11, 32, 18, 9, 24, 11]
                                                }],
                                                chart: {
                                                    height: 250,
                                                    type: 'area',
                                                    toolbar: {
                                                        show: false
                                                    },
                                                },
                                                markers: {
                                                    size: 4
                                                },
                                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                                fill: {
                                                    type: "gradient",
                                                    gradient: {
                                                        shadeIntensity: 1,
                                                        opacityFrom: 0.3,
                                                        opacityTo: 0.4,
                                                        stops: [0, 90, 100]
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: 2
                                                },
                                                xaxis: {
                                                    // type: 'datetime',
                                                    categories: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"]
                                                },
                                                tooltip: {
                                                    x: {
                                                        format: 'dd/MM/yy HH:mm'
                                                    },
                                                }
                                            }).render();
                                        });
                                    </script>
                                    <!-- End Line Chart -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
