
                            <div class="card card-body border p-sm-4 p-0">
                                <div class="table-responsive">
                                    <div class="mt-sm-0 my-4 text-center">
                                        <h1 class="card-title fw-bolder"><span class="text-gradient d-inline">@lang('miscellaneous.account.personal_infos.title')</span></h1>
                                    </div>

                                    <table class="table">
                                        <!-- First name -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.firstname')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->firstname) ? Auth::user()->firstname : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Last name -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.lastname')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td class="text-uppercase">{{ !empty(Auth::user()->lastname) ? Auth::user()->lastname : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Surname -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.surname')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td class="text-uppercase">{{ !empty(Auth::user()->surname) ? Auth::user()->surname : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Username -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.username')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->username) ? Auth::user()->username : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Gender -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.gender_title')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->gender) ? (Auth::user()->gender == 'F' ? __('miscellaneous.gender2') : __('miscellaneous.gender1')) : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Birth date -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.birth_date.label')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->birthdate) ? ucfirst(__('miscellaneous.on_date') . ' ' . explicitDate(Auth::user()->birthdate))  : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- E-mail -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.email')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->email) ? Auth::user()->email : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Phone -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.phone')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->phone) ? Auth::user()->phone : '- - - - - -' }}</td>
                                        </tr>

                                        <!-- Addresses -->
@if (!empty(Auth::user()->address_1) && !empty(Auth::user()->address_2))
                                        <tr>
                                            <td><strong>@lang('miscellaneous.addresses')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>
                                                <ul class="ps-0">
                                                    <li class="dktv-line-height-1_4 mb-2" style="list-style: none;">
                                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ Auth::user()->address_1 }}
                                                    </li>
                                                    <li class="dktv-line-height-1_4" style="list-style: none;">
                                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ Auth::user()->address_2 }}
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
@else
                                        <tr>
                                            <td><strong>@lang('miscellaneous.address.title')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->address_1) ? Auth::user()->address_1 : (!empty(Auth::user()->address_2) ? Auth::user()->address_2 : '- - - - - -') }}</td>
                                        </tr>
@endif

                                        <!-- P.O. box -->
                                        <tr>
                                            <td><strong>@lang('miscellaneous.p_o_box')</strong></td>
                                            <td>@lang('miscellaneous.colon_after_word')</td>
                                            <td>{{ !empty(Auth::user()->p_o_box) ? Auth::user()->p_o_box : '- - - - - -' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
