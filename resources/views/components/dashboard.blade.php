@extends('index')
@section('content')
@php
$planePais = DB::table('project_plans')
    ->where('status', '=', null)
    ->sum('pay');
// $chartSum = ($projects->sum('price') - ($planePais + $projects->sum('start_avance')) ) + $amountPaid + $reserveSum + $paidAvance + $remainingAvance + $unrestrainedReservePrice;

$chartSum = $reserveSum + $paidAvance + $remainingAvance + $unrestrainedReservePrice;
@endphp

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 col-md-5 col-lg-5 order-2 mb-4">
            <div class="card mb-4">
                <div class="card-body last-week-transaction">
                    <div class="card-body">
                        @if ($chartSum != 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2 comma">{{$chartSum}}</h2>
                                <span>ყველაფერი ერთად</span>
                            </div>
                            <div id="orderStatisticsChart1"></div>
                        </div>
                        <ul class="p-0 m-0">
                            {{-- <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">ჩასარიცხი თანხა</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{ $projects->sum('price') - ($planePais + $projects->sum('start_avance')) }}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / ($projects->sum('price') - ($planePais + $projects->sum('start_avance')) ) )}}" id="chasaricxi_tanxa">
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">ჩარიცხული თანხა</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$amountPaid}}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / $amountPaid )}}" id="charicxuli_tanxa">
                                </div>
                            </li> --}}
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გაქვითული მისაღები სარეზერვო თანხა</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$reserveSum}}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / $reserveSum )}}" id="gaqvituli_misagebi_sarezervo">
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გაქვითული ავანსი</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$paidAvance}}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / $paidAvance )}}" id="gaqvituli_avansi">
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გასაქვითი ავანსი</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$remainingAvance}}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / $remainingAvance )}}" id="gasaqviti_avansi">
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გასაქვით მისაღები სარეზერვო თანხა</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$unrestrainedReservePrice}}</small>
                                    </div>
                                    <input type="hidden" value="{{ 100 / ($chartSum / $unrestrainedReservePrice )}}" id="gasaqviti_misagebi_sarezervo">
                                </div>
                            </li>

                        </ul>  
                        @else
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2 comma">{{$freeGuarantees}}</h2>
                                <span>გამონთავისუფლებული თანხა</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-body last-week-transaction">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">ბოლოს შესრულებული გადახდები</h5>
                    </div>
                    <ul class="p-0 m-0">
                        @foreach($lastPlanes as $lastPlane)
                        <a href="project/{{$lastPlane->project_id}}">
                            <li class="d-flex mb-4 pb-1 last-plane">
                                <div class="avatar flex-shrink-0 me-3">
                                    <i class="menu-icon tf-icons bx bx-box pay-icon"></i>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{$lastPlane->name}}</h6>
                                    </div>
                                    <div class="user-progress  align-items-center gap-1">
                                        <span class="text-muted">ღირებულება</span>
                                        <h6 class="mb-0 comma">{{$lastPlane->price}} ₾</h6>
                                    </div>
                                </div>
                            </li>
                        </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 col-md-7 order-1 dash">
            <div class="col-md-12 col-lg-12 col-xl-12 order-0 mb-4">
                

                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2 comma">{{$projectsCost}}</h2>
                                <span>კონტრაქტის ღირებულება</span>
                            </div>
                            <div id="orderStatisticsChart"></div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გადახდილი თანხა</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$amountPaid}}</small>
                                    </div>
                                    <input type="hidden" value="{{100 / ($projectsCost / $amountPaid)}}" id="gadaxdili_chart">

                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">დარჩენილი გადასახადი</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$projects->sum('price') - ($amountPaid + $projects->sum('start_avance')) }}</small>
                                    </div>
                                    <input type="hidden" value=" {{100 / ($projectsCost / ($projects->sum('price') - ($amountPaid + $projects->sum('start_avance'))))}}" id="darchenili_chart">

                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">გადახდილი ავანსი</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold comma">{{$startAvanceSum}}</small>
                                    </div>
                                    <input type="hidden" value="{{100 / ($projectsCost / $startAvanceSum)}}" id="avance_chart">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">პროექტების რაოდენობა</span>
                            <h3 class="card-title color-main mb-2">{{$projectsQuantity}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">აქტიური პროექტები</span>
                            <h3 class="card-title color-green mb-2 comma">{{$activeProjectsQuantity}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დასრულებული პროექტები</span>
                            <h3 class="card-title  color-red mb-2 comma">{{$endedProjectsQuantity}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დასრულებული პროექტების თანხების ჯამი</span>
                            <h3 class="card-title  color-red mb-2 comma">{{$endedProjectsPrice}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">აქტიური პროექტების თანხების ჯამი</span>
                            <h3 class="card-title  color-red mb-2 comma">{{$activeProjectsPrice}}</h3>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">კონტრაქტის ღირებულება</span>
                            <h3 class="card-title  color-red mb-2 comma">{{$projectsCost}}</h3>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">შესრულებული სამუშაოთა ღირებულება</span>
                            <h3 class="card-title color-green mb-2 comma">{{$doneWork}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">გადახდილი თანხა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$amountPaid}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">შესასრულებელ სამუშაოთა ღირებულება</span>
                            <h3 class="card-title color-green mb-2 comma">{{$remainingWork}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დარჩენილი გადასახადი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$projects->sum('price') - ($planePais + $projects->sum('start_avance')) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">აქტიური გეგმების ღირებულება</span>
                            <h3 class="card-title color-green mb-2 comma">{{$activePlane}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">გადახდილი ავანსი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$startAvanceSum}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">გაუქვითავი ავანსი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$remainingAvance}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დაკავებული ავანსი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$paidAvance}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1"> სარეზერვო თანხა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$reserveSum}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დაუკავებელი სარეზერვო თანხა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$unrestrainedReservePrice}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">დაკავებული სარეზერვო თანხა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$busyReserve}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">რეზერვის გარანტიის ჯამი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$reserveStartGuarentee}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">ავანსის გარანტიის ჯამი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$avanceStartGuarentee}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">მიმდინარე გარანტიების ოდენობა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$activeGuarenteePrice}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">ქეშით უზრუნველყოფილი გარანტიები</span>
                            <h3 class="card-title color-green mb-2 comma">{{$grantsCash}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">ქონებით უზრუნველყოფილი გარანტიები</span>
                            <h3 class="card-title color-green mb-2 comma">{{$grantUdzravi}}</h3>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">გარანტიების ლიმიტი</span>
                            <h3 class="card-title color-green mb-2 comma">{{$grantLimit}}</h3>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-6 col-xl-4 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">გამონთავისუფლებული თანხა</span>
                            <h3 class="card-title color-green mb-2 comma">{{$freeGuarantees}}</h3>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>

    </div>
</div>
@stop