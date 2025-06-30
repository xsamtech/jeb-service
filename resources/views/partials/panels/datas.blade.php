
                    <div class="row">
                        <div class="col-lg-6 col-md-7 mx-auto">
                            <div class="card card-body border">
                                <h4 class="card-title mb-0 text-gradient text-center fw-bold">Modifier le panneau</h4>

                                <!-- Find by is_available -->
                                <form method="POST" action="{{ route('dashboard.panel.datas', ['id' => $selected_panel['id']]) }}" class="mx-auto mt-3">
@csrf
                                    <div class="row g-3">
                                        <!-- Dimensions -->
                                        <div class="col-sm-6">
                                            <label for="dimensions" class="form-label fw-bold">Dimensions</label>
                                            <input type="text" name="dimensions" class="form-control" id="dimensions" value="{{ $selected_panel['dimensions'] }}">
                                        </div>

                                        <!-- Format -->
                                        <div class="col-sm-6">
                                            <label for="format" class="form-label fw-bold">Format</label>
                                            <input type="text" name="format" class="form-control" id="format" value="{{ $selected_panel['format'] }}">
                                        </div>

                                        <!-- Price -->
                                        <div class="col-sm-6">
                                            <label for="price" class="form-label fw-bold">Prix</label>
                                            <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ $selected_panel['price'] }}">
                                        </div>

                                        <!-- Location -->
                                        <div class="col-sm-6">
                                            <label for="location" class="form-label fw-bold">Site / Emplacement</label>
                                            <textarea class="form-control" name="location" id="location">{{ $selected_panel['location'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <label class="form-label fw-bold">Est disponible</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_available" id="is_available1" value="1"{{ $selected_panel['is_available'] == 1 ? ' checked' : '' }}>
                                                <label class="form-check-label" for="is_available1">Oui</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_available" id="is_available0" value="0"{{ $selected_panel['is_available'] == 0 ? ' checked' : '' }}>
                                                <label class="form-check-label" for="is_available0">Non</label>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn bg-gradient-primary-to-secondary w-100 mt-3 rounded-pill text-white">@lang('miscellaneous.register')</button>
                                </form>
                            </div>
                        </div>
                    </div>

