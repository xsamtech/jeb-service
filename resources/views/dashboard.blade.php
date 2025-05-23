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
                <div class="container px-sm-5">
                    <div class="row g-3">
                        <!-- Customers -->
                        <div class="col-lg-5 col-sm-6 ms-auto">
                            <div class="card card-body bg-primary flex-row align-items-center">
                                <i class="bi bi-person display-1 text-white"></i>
                                <div class="ms-4">
                                    <h2 class="text-white fw-light mt-2">Clients</h2>
                                    <h5 class="text-white">41 225</h5>
                                </div>
                                <a role="button" class="stretched-link"></a>
                            </div>
                        </div>

                        <!-- Customers -->
                        <div class="col-lg-5 col-sm-6 me-auto">
                            <div class="card card-body bg-danger flex-row align-items-center">
                                <i class="bi bi-envelope display-1 text-white"></i>
                                <div class="ms-4">
                                    <h2 class="text-white fw-light mt-2">Messages</h2>
                                    <h5 class="text-white">317</h5>
                                </div>
                                <a role="button" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Table Section-->
            <section class="pb-3">
                <div class="container px-5">
                    <div class="row g-3">
                        <!-- Customers -->
                        <div class="col-lg-6 col-sm-10 mx-auto">
                            <div class="card card-body border">
                                <h3 class="card-title text-center fw-bold mb-3">Comptes</h3>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Total des commandes</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $total_orders }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Commandes payées</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $orders_paid }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Dîme (10%)</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $tithe }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">TVA (16%)</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $tva }} $</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Reste de l'argent</th>
                                                <td style="text-align: right; padding-right: 30px;">{{ $rest_of_money }} $</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
