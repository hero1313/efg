@extends('index')
@section('content')
    <form action="/update-forecast" method="post">
        @csrf
        @method('PUT')
        <div class="container-fluid flex-grow-1 container-p-y">
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                    პროექტების სია
                </button>
            </p>
            <div class="collapse forecast-collapse" id="collapseExample">
                <div class="card card-body">
                    @foreach ($checkedProjects as $project)
                        <div class="forecast-list row mb-2 forecasts-row">
                            <div class="row col-12 d-flex">
                                <div class="col-2"> <input type="checkbox" name='forecast_index[{{ $project->id }}]'
                                        value="1" {{ $project->forecast_index == 1 ? 'checked' : '' }} class="check"
                                        onchange="showForecast({{ $project->id }});"
                                        id="forecast_checkbox_{{ $project->id }}">
                                </div>
                                <div class="col-10"> {{ $project->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($uncheckedProjects as $project)
                        <div class="forecast-list row mb-2 forecasts-row">
                            <div class="row col-12 d-flex">
                                <div class="col-2"> <input type="checkbox" name='forecast_index[{{ $project->id }}]'
                                        value="1" {{ $project->forecast_index == 1 ? 'checked' : '' }} class="check"
                                        onchange="showForecast({{ $project->id }});"
                                        id="forecast_checkbox_{{ $project->id }}">
                                </div>
                                <div class="col-10"> {{ $project->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- <form action="/project-forecast" method="get">
            <div class="form-group d-flex mt-3 mb-3 search-div">
                <input type="text" name='search' class="form-control mt-2 search" value="{{ $search }}"
                    placeholder="მოძებნე პროექტი">
                <button class="btn btn-primary">ძებნა</button>
            </div>
        </form> --}}
            <div class="card mt-5">
                <h5 class="card-header">პროექტები</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped project-table">
                        <thead>
                            <tr>
                                <th>დასახელება</th>
                                <th>ღირებულება</th>
                                <th>რეზერვის %</th>
                                {{-- <th>რეზერვი</th> --}}
                                <th>ავანსის %</th>
                                {{-- <th>ავანსი</th> --}}
                                <th>სერთიფიკატის ღირებულება</th>
                                <th>კალკულაცია</th>
                                <th>გადახდილი თანხა</th>
                                <th>დარჩენილი გადასახადი</th>
                                <th>მისაღები თანხა</th>
                                <th>გამონთავისუფლებული</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($checkedProjects as $project)
                                @php
                                    $planePay = DB::table('project_plans')
                                        ->where('project_id', '=', $project->id)
                                        ->where('status', '=', null)
                                        ->sum('pay');
                                @endphp
                                <tr class=" forecast-{{ $project->id }}" style="cursor: pointer;">
                                    <td class="edit pro-name " href="{{ route('show.project', $project->id) }}">
                                        {{ $project->name }}</td>
                                    <td class="edit comma" href="{{ route('show.project', $project->id) }}">
                                        {{ $project->price }}</td>
                                    <td class="edit" href="{{ route('show.project', $project->id) }}">
                                        {{ $project->reserve_percent }}%</td>
                                    {{-- <td>დათვლილი რეზერვი</td> --}}
                                    <td class="" href="{{ route('show.project', $project->id) }}">
                                        <input type="number" class="form-control" value="{{ $project->avance_percent }}"
                                            id="avance_percent_{{ $project->id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="forecast_plane[{{ $project->id }}]"
                                            id="cert_price_{{ $project->id }}" class="form-control mt-2 "
                                            placeholder="სერთიფიკატის თანხა"
                                            value="{{ $project->forecast_plane > 0 ? $project->forecast_plane : '' }}">
                                    </td>
                                    {{-- <td>დათვლილი ავანსი</td> --}}
                                    <td>
                                        <button class="btn btn-primary" type="button"
                                            onclick="calculateCertPrice({{ $project->id }})"
                                            id="calc_{{ $project->id }}">კალკულაცია</button>
                                    </td>
                                    <td id="gadaxdili_td_{{ $project->id }}">
                                        {{ $planePay + $project->start_avance }}
                                    </td>
                                    <input id="gadaxdili_{{ $project->id }}" type="hidden"
                                        value="{{ $planePay + $project->start_avance }}">
                                    <td id="gadasaxdeli_td_{{ $project->id }}">
                                        {{ $project->price - ($planePay + $project->start_avance) }}
                                    </td>
                                    <input id="gadasaxdeli_{{ $project->id }}" type="hidden"
                                        value="{{ $project->price - ($planePay + $project->start_avance) }}">

                                    <td>
                                        <div class="btn btn-success btn-forecast" id="calc_price_{{ $project->id }}">
                                            {{ $project->forecast_calculate > 0 ? $project->forecast_calculate : '' }}
                                        </div>
                                        <input id="datvlili_{{ $project->id }}" name="datvlili[{{ $project->id }}]"
                                            type="hidden" value="{{ $project->forecast_calculate }}">
                                    </td>
                                    <td>
                                        <div class="btn btn-success btn-forecast"
                                            id="calc_gamontavisuflebuli_{{ $project->id }}">
                                            {{ $project->forecast_gamontavisuflebuli > 0 ? $project->forecast_gamontavisuflebuli : '' }}
                                        </div>
                                        <input id="gamontavisuflebuli_{{ $project->id }}"
                                            name="gamontavisuflebuli[{{ $project->id }}]" type="hidden"
                                            value="{{ $project->forecast_gamontavisuflebuli }}">
                                    </td>
                                    <input type="hidden" value="{{ $project->reserve_percent }}"
                                        id="reserve_percent_{{ $project->id }}">



                                    @php
                                        $guarantee = DB::table('guarantees')
                                            ->where('project_id', '=', $project->id)
                                            ->sum('price');
                                    @endphp

                                    <input type="hidden" value="{{ $guarantee }}" id="guarantee_{{ $project->id }}">
                                    <input type="hidden" value="{{ $project->avance }}" id="avance_{{ $project->id }}">

                                </tr>
                            @endforeach
                            @foreach ($uncheckedProjects as $project)
                                @php
                                    $planePay = DB::table('project_plans')
                                        ->where('project_id', '=', $project->id)
                                        ->where('status', '=', null)
                                        ->sum('pay');
                                @endphp
                                <tr class=" forecast-tr forecast-{{ $project->id }}" style="cursor: pointer;">
                                    <td class="edit pro-name " href="{{ route('show.project', $project->id) }}">
                                        {{ $project->name }}</td>
                                    <td class="edit comma" href="{{ route('show.project', $project->id) }}">
                                        {{ $project->price }}</td>
                                    <td class="edit" href="{{ route('show.project', $project->id) }}">
                                        {{ $project->reserve_percent }}%</td>
                                    {{-- <td>დათვლილი რეზერვი</td> --}}
                                    <td class="" href="{{ route('show.project', $project->id) }}">
                                        <input type="number" class="form-control"
                                            value="{{ $project->avance_percent }}"
                                            id="avance_percent_{{ $project->id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="forecast_plane[{{ $project->id }}]"
                                            id="cert_price_{{ $project->id }}" class="form-control mt-2 "
                                            placeholder="სერთიფიკატის თანხა"
                                            value="{{ $project->forecast_plane > 0 ? $project->forecast_plane : '' }}">
                                    </td>
                                    {{-- <td>დათვლილი ავანსი</td> --}}
                                    <td>
                                        <button class="btn btn-primary" type="button"
                                            onclick="calculateCertPrice({{ $project->id }})"
                                            id="calc_{{ $project->id }}">კალკულაცია</button>
                                    </td>
                                    <td id="gadaxdili_td_{{ $project->id }}">
                                        {{ $planePay + $project->start_avance }}
                                    </td>
                                    <input id="gadaxdili_{{ $project->id }}" type="hidden"
                                        value="{{ $planePay + $project->start_avance }}">
                                    <td id="gadasaxdeli_td_{{ $project->id }}">
                                        {{ $project->price - ($planePay + $project->start_avance) }}
                                    </td>
                                    <input id="gadasaxdeli_{{ $project->id }}" type="hidden"
                                        value="{{ $project->price - ($planePay + $project->start_avance) }}">

                                    <td>
                                        <div class="btn btn-success btn-forecast" id="calc_price_{{ $project->id }}">
                                            {{ $project->forecast_calculate > 0 ? $project->forecast_calculate : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn btn-success btn-forecast"
                                            id="calc_gamontavisuflebuli_{{ $project->id }}">
                                            {{ $project->forecast_gamontavisuflebuli > 0 ? $project->forecast_gamontavisuflebuli : '' }}
                                        </div>
                                        <input id="gamontavisuflebuli_{{ $project->id }}"
                                            name="gamontavisuflebuli[{{ $project->id }}]" type="hidden"
                                            value="{{ $project->forecast_gamontavisuflebuli }}">
                                    </td>
                                    <input type="hidden" value="{{ $project->reserve_percent }}"
                                        id="reserve_percent_{{ $project->id }}">



                                    @php
                                        $guarantee = DB::table('guarantees')
                                            ->where('project_id', '=', $project->id)
                                            ->sum('price');
                                    @endphp

                                    <input type="hidden" value="{{ $guarantee }}" id="guarantee_{{ $project->id }}">
                                    <input type="hidden" value="{{ $project->avance }}" id="avance_{{ $project->id }}">
                                </tr>
                            @endforeach
                            <tr class=" forecast " style="cursor: pointer;">
                                <td>
                                    ჯამი
                                </td>
                                <td class="comma">
                                    {{ $checkedProjects->sum('price') }}
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td class="comma">
                                    {{ $checkedProjects->sum('forecast_plane') }}
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td class="comma">
                                    {{ $checkedProjects->sum('forecast_calculate') }}
                                </td>
                                <td class="comma">
                                    {{ $checkedProjects->sum('forecast_gamontavisuflebuli') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-primary mt-3" type="submit">შენახვა</button>
    </form>
    </div>
    </div>

    <!-- Button trigger modal -->
    <script>
        function calculateCertPrice($id) {

            if ($('#cert_price_' + $id).val()) {
                $('#calc_price_' + $id).val('')
                $calculateReservePrice = $('#cert_price_' + $id).val() - ($('#cert_price_' + $id).val() / 100 * $(
                    '#reserve_percent_' + $id).val())
                $calculateAvancePrice = $calculateReservePrice / 100 * $('#avance_percent_' + $id).val()
                $gamontavisuflebuli_calc = $('#avance_' + $id).val() - $('#guarantee_' + $id).val() - $calculateAvancePrice
                    if ($gamontavisuflebuli_calc < 0) {
                        $gamontavisuflebuli =  $gamontavisuflebuli_calc * -1
                    }
                    else{
                        $gamontavisuflebuli = 0
                    }
                $calculateLastPrice = $calculateReservePrice - $calculateAvancePrice

                $gadaxdili = parseFloat($('#gadaxdili_' + $id).val()) + parseFloat($calculateLastPrice);
                $gadasaxdeli = $('#gadasaxdeli_' + $id).val() - $calculateLastPrice

                $('#gadaxdili_td_' + $id).text($gadaxdili)
                $('#gadasaxdeli_td_' + $id).text($gadasaxdeli)
                $('#calc_price_' + $id).text($calculateLastPrice)
                $('#datvlili_' + $id).val($calculateLastPrice)
                $('#gamontavisuflebuli_' + $id).val($gamontavisuflebuli)
                $('#calc_gamontavisuflebuli_' + $id).text($gamontavisuflebuli)

            } else {
                alert('თანხა არარის შეყვანილი')
            }
        }


        function showForecast($id) {
            $('.forecast-' + $id).toggle();
        }
        $(document).ready(function() {
            $('.edit').click(function() {
                window.location = $(this).attr('href');
                return false;
            });
        });
    </script>
@stop
