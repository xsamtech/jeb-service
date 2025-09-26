
                            <div class="card card-body">
                                <div class="row">
@if ($entity == 'panel')
                                    <!-- Item details -->
                                    <div class="col-lg-7 col-sm-6">
                                    </div>

                                    <!-- Update item -->
                                    <div class="col-lg-5 col-sm-6">
                                        <div class="card card-body">
                                            <h4 class="card-title mb-0 text-gradient text-center fw-bold">Modifier le panneau</h4>

                                            <!-- Find by is_available -->
                                            <form method="POST" action="{{ route('dashboard.panel.datas', ['id' => $selected_item['id']]) }}" class="mx-auto mt-3">
    @csrf
                                                <div class="row g-3">
                                                    <!-- Dimensions -->
                                                    <div class="col-sm-6">
                                                        <label for="dimensions" class="form-label fw-bold">Dimensions</label>
                                                        <input type="text" name="dimensions" class="form-control" id="dimensions" value="{{ $selected_item['dimensions'] }}">
                                                    </div>

                                                    <!-- Format -->
                                                    <div class="col-sm-6">
                                                        <label for="format" class="form-label fw-bold">Format</label>
                                                        <select name="format" id="format" class="form-select">
                                                            <option {{ explode(' ', $selected_item['format'])[0] == 'Portrait' ? 'selected' : '' }}>Portrait</option>
                                                            <option {{ explode(' ', $selected_item['format'])[0] == 'Paysage' ? 'selected' : '' }}>Paysage</option>
                                                        </select>
                                                    </div>

                                                    <!-- Number of faces -->
                                                    <div class="col-sm-6">
                                                        <label for="number_of_faces" class="form-label fw-bold">Nombre des faces</label>
                                                        <select name="number_of_faces" id="number_of_faces" class="form-select">
                                                            <option {{ count($selected_item['faces']) == 1 ? 'selected' : '' }} value="1">1 Face (Recto)</option>
                                                            <option {{ count($selected_item['faces']) == 2 ? 'selected' : '' }} value="2">2 Faces (Recto/Verso)</option>
                                                        </select>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="col-sm-6">
                                                        <label for="price" class="form-label fw-bold">Prix unitaire</label>
                                                        <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ $selected_item['price'] }}">
                                                    </div>

                                                    <!-- Location -->
                                                    <div class="col-12">
                                                        <label for="location" class="form-label fw-bold">Site / Emplacement</label>
                                                        <textarea class="form-control" name="location" id="location">{{ $selected_item['location'] }}</textarea>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn bg-gradient-primary-to-secondary w-100 mt-3 rounded-pill text-white">@lang('miscellaneous.register')</button>
                                            </form>
                                        </div>
                                    </div>
    
@else
                                    <!-- Item details -->
                                    <div class="col-lg-7 col-sm-6">
                                    </div>

                                    <!-- Update item -->
                                    <div class="col-lg-5 col-sm-6">
                                    </div>
    
@endif
                                </div>
                            </div>
