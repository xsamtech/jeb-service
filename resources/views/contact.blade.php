@extends('layouts.guest', ['page_title' => 'Nous contacter'])

@section('guest-content')

            <!-- Services Section-->
            <section class="pb-3">
                <div class="container-fluid container-lg px-5">
                    <!-- Contact form-->
                    <div class="bg-light rounded-4 py-5 px-4 px-md-5">
                        <div class="text-center mb-5">
                            <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3"><i class="bi bi-envelope"></i></div>
                            <h1 class="fw-bolder">Nous contacter</h1>
                            <p class="lead fw-normal text-muted mb-0">Que peut-on faire pour vous ?</p>
                        </div>
                        <div class="row gx-5 justify-content-center">
                            <div class="col-lg-8 col-xl-6">
                                <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                                    <!-- Name input-->
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="name" type="text" placeholder="Entrer votre nom ..." data-sb-validations="required" />
                                        <label for="name">Nom complet</label>
                                        <div class="invalid-feedback" data-sb-feedback="name:required">Un nom est requis.</div>
                                    </div>
                                    <!-- Email address input-->
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="email" type="email" placeholder="name@example.com" data-sb-validations="required,email" />
                                        <label for="email">Adresse e-mail</label>
                                        <div class="invalid-feedback" data-sb-feedback="email:required">Un e-mail est requis.</div>
                                        <div class="invalid-feedback" data-sb-feedback="email:email">L'e-mail n'est pas valide.</div>
                                    </div>
                                    <!-- Phone number input-->
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" />
                                        <label for="phone">N° de téléphone</label>
                                        <div class="invalid-feedback" data-sb-feedback="phone:required">Un n° de téléphone est requis.</div>
                                    </div>
                                    <!-- Message input-->
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="message" type="text" placeholder="Entrez votre message ici..." style="height: 10rem" data-sb-validations="required"></textarea>
                                        <label for="message">Message</label>
                                        <div class="invalid-feedback" data-sb-feedback="message:required">Un message is requis.</div>
                                    </div>
                                    <!-- Submit success message-->
                                    <!---->
                                    <!-- This is what your users will see when the form-->
                                    <!-- has successfully submitted-->
                                    <div class="d-none" id="submitSuccessMessage">
                                        <div class="text-center mb-3">
                                            <div class="fw-bolder">Message envoyé avec succès!</div>
                                        </div>
                                    </div>
                                    <!-- Submit error message-->
                                    <!---->
                                    <!-- This is what your users will see when there is-->
                                    <!-- an error submitting the form-->
                                    <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Erreur lors de l'envoi du message !</div></div>
                                    <!-- Submit Button-->
                                    <div class="d-grid"><button class="btn btn-primary btn-lg disabled" id="submitButton" type="submit">Envoyer</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

@endsection
