@extends('layouts.guest', ['page_title' => 'Nos services'])

@section('guest-content')

            <!-- Services Section-->
            <section class="pb-3">
                <div class="container px-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Nos services</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-11 col-xl-9 col-xxl-8">
                            <!-- Service Card 1-->
                            <div class="card bg-light overflow-hidden shadow rounded-4 mb-5">
                                <div class="card-body p-0">
                                    <div class="d-sm-flex align-items-center">
                                        <img class="img-fluid" src="https://dummyimage.com/300x400/343a40/6c757d" alt="..." />
                                        <div class="p-5">
                                            <h2 class="fw-bolder text-danger">Panneaux de pub</h2>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum illum aperiam placeat esse? Mollitia omnis minima saepe recusandae libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!</p>
                                            <button class="btn btn-primary mt-3"><i class="bi bi-cart3 me-2"></i>Louer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Card 2-->
                            <div class="card bg-light overflow-hidden shadow rounded-4 mb-5">
                                <div class="card-body p-0">
                                    <div class="d-sm-flex align-items-center">
                                        <div class="p-5">
                                            <h2 class="fw-bolder text-danger">Imprimerie</h2>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum illum aperiam placeat esse? Mollitia omnis minima saepe recusandae libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!</p>
                                            <button class="btn btn-primary mt-3"><i class="bi bi-cart3 me-2"></i>Louer</button>
                                        </div>
                                        <img class="img-fluid" src="https://dummyimage.com/300x400/343a40/6c757d" alt="..." />
                                    </div>
                                </div>
                            </div>

                            <!-- Service Card 3-->
                            <div class="card bg-light overflow-hidden shadow rounded-4 mb-5">
                                <div class="card-body p-0">
                                    <div class="d-sm-flex align-items-center">
                                        <img class="img-fluid" src="https://dummyimage.com/300x400/343a40/6c757d" alt="..." />
                                        <div class="p-5">
                                            <h2 class="fw-bolder text-danger">Design</h2>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum illum aperiam placeat esse? Mollitia omnis minima saepe recusandae libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!</p>
                                            <button class="btn btn-primary mt-3"><i class="bi bi-cart3 me-2"></i>Louer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
