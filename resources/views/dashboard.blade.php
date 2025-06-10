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
                <div class="container px-lg-5">
                    <div class="row g-3 px-lg-3">
                        <div class="col-lg-6 col-sm-5 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-person-gear display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Utilisateur{{ count($all_users) > 1 ? 's' : '' }}</h3>
                                    <h5 class="text-white">{{ count($all_users) }}</h5>
                                </div>
                                <a href="{{ route('dashboard.users') }}" class="stretched-link"></a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-7 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-piggy-bank display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Caisse</h3>
                                    <h5 class="text-white">{{ number_format(data_get($balance_summary, 'leftover_money', 0), 2) . ' $' }}</h5>
                                </div>
                                <a href="{{ route('dashboard.statistics') }}" class="stretched-link"></a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-5 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-card-heading display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Panneau{{ count($all_panels) > 1 ? 'x' : '' }}</h3>
                                    <h5 class="text-white">{{ count($all_panels) }}</h5>
                                </div>
                                <a href="{{ route('dashboard.panels') }}" class="stretched-link"></a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-7 mx-auto">
                            <div class="card card-body bg-gradient-primary-to-secondary flex-row align-items-center">
                                <i class="bi bi-coin display-1 text-white"></i>
                                <div class="ms-4">
                                    <h3 class="text-white fw-light mt-2">Dépenses du mois</h3>
                                    <h5 class="text-white">{{ number_format(data_get($balance_summary, 'total_expenses', 0), 2) . ' $' }}</h5>
                                </div>
                                <a href="{{ route('dashboard.expenses') }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Table Section-->
            <section class="pb-3">
                <div class="container px-lg-5">
                    <div class="row g-3 px-lg-3">
                        <div class="col-lg-6">
                            <div class="card card-body border">
                                <h4 class="card-title text-center fw-bold mb-3">Comptes du mois</h4>
                                <div class="table-responsive">
    @if ($currentSummary)
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Total des gains</th>
                                                <td style="text-align: right; padding-right: 30px;">
                                                    {{ $balance_summary['total_earnings'] }} $
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Total des dépenses</th>
                                                <td style="text-align: right; padding-right: 30px;">
                                                    {{ $balance_summary['total_expenses'] }} $
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Reste à la caisse</th>
                                                <td style="text-align: right; padding-right: 30px;">
                                                    {{ $balance_summary['in_the_box'] }} $
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
    @else
                                        <p class="mb-0 lead text-center">Aucune donnée pour le mois courant.</p>
    @endif                                
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h4 class="card-title text-center fw-bold mb-3">Rapports de la semaine</h4>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>
                                    <script>
document.addEventListener("DOMContentLoaded", () => {
    const chart = new ApexCharts(document.querySelector("#reportsChart"), {
        series: [
            {
                name: 'Gains',
                data: @json($chartEarnings),
            },
            {
                name: 'Dépenses',
                data: @json($chartExpenses),
            },
            {
                name: 'Panneaux',
                data: @json($chartPanels),
            }
        ],
        chart: {
            height: 250,
            type: 'area',
            toolbar: { show: false },
        },
        markers: { size: 4 },
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
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: @json($chartLabels),
        },
        tooltip: {
            x: {
                show: true,
                formatter: function (val) {
                    return val; // format "2025-W21"
                }
            },
        }
    });

    chart.render();
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
