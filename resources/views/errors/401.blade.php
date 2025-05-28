@extends('layouts.auth', ['page_title' => __('notifications.' . $exception->getStatusCode() . '_title')])

@section('auth-content')

            <!-- Title Section-->
            <section class="pb-1">
                <div class="container px-5">
                    <div class="text-center mb-5">
                        <h1 class="display-1 fw-bolder mb-0"><span class="text-gradient d-inline">401</span></h1>
                    </div>
                </div>
            </section>

            <!-- Login Section-->
            <section class="pb-3">
                <div class="container px-5">
                    <div class="row g-3">
                        <!-- Customers -->
                        <div class="col-lg-6 col-sm-7 mx-auto">
                            <div class="card card-body text-center">
                                <h2 class="card-title fw-bold">@lang('notifications.401_title')</h2>
                                <p class="card-text">@lang('notifications.401_description')</p>
                                <a href="{{ route('dashboard.home') }}" class="btn btn-danger w-75 mx-auto mt-3 rounded-pill">@lang('miscellaneous.back_home')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
