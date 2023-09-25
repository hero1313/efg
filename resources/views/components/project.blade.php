@extends('index')
@section('content')
    @php
        $planePay = DB::table('project_plans')
            ->where('project_id', '=', $project->id)
            ->where('status', '=', null)
            ->sum('pay');
    @endphp
    <div class="row">
        <div class="col-md-5 col-12">
            <img class="project-img"
                src="https://imageio.forbes.com/b-i-forbesimg/houzz/files/2013/12/contemporary-exterior.jpg?format=jpg&width=500">
        </div>
        <div class="col-md-7 col-12 row mt-5">
            <div class="col-12">
                <div class="card plane-card" style="font-size: 16px;">
                    <h5 class="project-dashboard-card">სახელი</h5>
                    {{ $project->name }}
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-6">
                <div class="card plane-card">
                    <h5 class="project-dashboard-card">კონტრაქტის ღირებულება</h5>
                    <span class="comma">{{ $project->price }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-6">
                <div class="card plane-card">
                    <h5 class="project-dashboard-card">სტატუსი</h5>
                    @if ($project->status == 1)
                        <button class='btn btn-success'>მიმდინარე</button>
                    @elseif($project->status == null)
                        <button class='btn btn-danger'>დასრულებული</button>
                    @endif
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-red">
                    <h5 class="project-dashboard-card">გადახდილი ავანსი</h5>
                    <span class="comma">{{ $project->start_avance }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-green">
                    <h5 class="project-dashboard-card">სერთიფიკატიდან გადახდილი თანხა</h5>
                    <span class="comma">{{ $planePay }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-green">
                    <h5 class="project-dashboard-card">ჯამურად გადახდილი თანხა</h5>
                    <span class="comma">{{ $planePay + $project->start_avance }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-red">
                    <h5 class="project-dashboard-card">დარჩენილი გადასახადი</h5>
                    <span class="comma">{{ $project->price - ($planePay + $project->start_avance) }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-green">
                    <h5 class="project-dashboard-card ">დაწყების თარიღი</h5>
                    {{ $project->start_date }}
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-red">
                    <h5 class="project-dashboard-card ">დასრულების თარიღი</h5>
                    {{ $project->end_date }}
                </div>
            </div>

            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-green">
                    <h5 class="project-dashboard-card">შესრულებული სამუშაოთა ღირებულება</h5>
                    <span class="comma">{{ $done }}</span>

                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card color-red">
                    <h5 class="project-dashboard-card ">შესასრულებელ სამუშაოთა ღირებულება</h5>
                    <span class="comma">{{ $remaining }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card plane-card detail-card" >
                    <h5 class="project-dashboard-card">პროექტის დეტალები</h5>
                    <button class='btn btn-primary add-project' data-toggle="modal" data-target="#project_details">დეტალები</button>
                </div>
            </div>

        </div>
    </div>

    <div class="add-div">
        @if(Auth::user()->role == 1) 
        <button class='btn btn-primary add-project' data-toggle="modal" data-target="#add_plane">გეგმის დამატება</button>
        @endif
    </div>
    <div class="add-div" style="opacity: 0;">
        @if(Auth::user()->role == 1) 
        <button class='btn btn-primary add-project' data-toggle="modal" data-target="#add_plane">გეგმის დამატება</button>
        @endif
    </div>

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mt-5">
            <h5 class="card-header">პროექტის გეგმა </h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>დასახელება</th>
                            <th>ჯამური ღირებულება</th>
                            <th>დასრულების თარიღი</th>
                            <th>გადახდის თარიღი</th>
                            <th>ავანსის პროცენტი</th>
                            <th>ავანსი</th>
                            <th>რეზერვის პროცენტი</th>
                            <th>რეზერვი</th>
                            <th>გადასახდელი თანხა</th>
                            <th>სტატუსი</th>
                            <th>გარანტიის სტატუსი</th>
                            <th>PDF</th>
                            <th>რედაქტირება</th>
                            <th>წაშლა</th>

                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($projectPlanes as $projectPlane)
                            <tr>
                                <td>
                                    <h6>{{ $projectPlane->name }}</h6>
                                </td>
                                <td class="comma">{{ $projectPlane->price }} ₾</td>
                                <td>{{ $projectPlane->deadline_date }}</td>
                                <td>{{ $projectPlane->pay_date }}</td>
                                <td>{{ $projectPlane->avance_percent != null ? $projectPlane->avance_percent : '0' }} %
                                </td>
                                <td class="comma">
                                    {{ $projectPlane->avance_price != null ? $projectPlane->avance_price : '0' }} ₾</td>
                                <td>
                                    {{ $projectPlane->reserve_percent != null ? $projectPlane->reserve_percent : '0' }} %
                                </td>
                                <td class="comma">
                                    {{ $projectPlane->reserve_price != null ? $projectPlane->reserve_price : '0' }} ₾</td>
                                <td class="comma">{{ $projectPlane->pay != null ? $projectPlane->pay : '0' }} ₾</td>
                                <td>
                                    @if ($projectPlane->status == 1)
                                        <button class='btn btn-success'>აქტიური</button>
                                    @elseif($projectPlane->status == null)
                                        <button class='btn btn-danger'>დასრულებული</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($projectPlane->guarantee_status == 0)
                                        <button class='btn btn-danger' data-toggle="modal"
                                            data-target="#edit_guarantee_status_{{ $projectPlane->id }}">ჩასარიცხი</button>
                                    @elseif($projectPlane->guarantee_status == 1)
                                        <button class='btn btn-success' data-toggle="modal"
                                            data-target="#edit_guarantee_status_{{ $projectPlane->id }}">ჩარიცხული</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('download.pdf', $projectPlane->id) }}">
                                        <button class='btn btn-primary'>PDF</button>
                                    </a>
                                </td>
                                <td>
                                    @if ($projectPlane->status == 1)
                                    @if(Auth::user()->role == 1) 
                                        <button class='btn btn-primary' data-toggle="modal" data-target="#edit_plane_{{ $projectPlane->id }}">რედაქტირება</button>
                                    @endif
                                        
                                    @endif
                                </td>
                                <td>
                                    @if ($projectPlane->status == 1)
                                        @if(Auth::user()->role == 1) 
                                            <button class='btn btn-danger' data-toggle="modal"
                                                data-target="#delete_{{ $projectPlane->id }}">წაშლა</button>
                                        @endif
                                        <!-- <form action="{{ route('delete.project_plane', $projectPlane->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger">წაშლა</button>
                                            </form> -->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="add_plane" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">გეგმის დამატება</h5>
                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action='{{ route('store.project_plane', $project->id) }}' method='post'>
                        @csrf
                        <div class='row'>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">დასახელება</label>
                                <input type="text" name='name' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ანაზღაურება</label>
                                <input type="number" step="0.01" name='price' required
                                    class="form-control mt-2 price">
                            </div>
                            @if ($project->reserve < $project->start_reserve)
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">რეზერვის %</label>
                                    <input type="number" id="reserve_percent" class="form-control mt-2 reserve_percent"
                                        name='reserve_percent' required min='0' max='100'
                                        value="{{ $project->reserve_percent }}">
                                </div>
                            @else
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">რეზერვის %</label>
                                    <input type="number" id="reserve_percent" disabled name='reserve_percent'
                                        min='0' max='100' value="0" placeholder="რეზერვი ამოიწურა"
                                        class="form-control mt-2 reserve_percent">
                                </div>
                            @endif
                            <div class="form-group col-12 col-md-4 mt-3">
                                <label for="exampleInputEmail1">რეზერვის თანხა</label>
                                <input type="number" name='reserve_price' step="0.01"
                                    class="form-control reserve_price mt-2">
                            </div>

                            <div class="form-group col-12 col-md-4 mt-3">
                                <button type="button" onclick="calculateReserve()"
                                    class="btn btn-primary calc-btn">დათვლა</button>
                            </div>

                            @if ($project->avance > 0)
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">ავანსის %</label>
                                    <input type="number" id="avance_percent" name='avance_percent' required
                                        min='0' max='100' value="{{ $project->avance_percent }}"
                                        class="form-control mt-2 avance_percent">
                                </div>
                            @else
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">ავანსის %</label>
                                    <input type="text" id="avance_percent" name='avance_percent' disabled
                                        placeholder="პროექტის ავანსი ამოიწურა" class="form-control mt-2 avance_percent">
                                </div>
                            @endif

                            <div class="form-group col-12 col-md-4 mt-3">
                                <label for="exampleInputEmail1">ავანსის თანხა</label>
                                <input type="number" name='avance_price' step="0.01"
                                    class="form-control avance_price mt-2">
                            </div>
                            <div class="form-group col-12 col-md-4 mt-3">
                                <button type="button" onclick="calculatePrice()"
                                    class="btn btn-primary calc-btn">დათვლა</button>
                            </div>
                            <!-- <div class="form-group col-12 col-md-6 mt-3">
                                        <label for="exampleInputEmail1">შესრულების ვადა</label>
                                        <input type="date" name='deadline_date' required class="form-control mt-2">
                                    </div> -->
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ანაზღაურების თარიღი</label>
                                <input type="date" name='pay_date' required class="form-control mt-2">
                            </div>
                            <!-- <div class="form-group col-12 col-md-12 mt-3">
                                        <label for="exampleInputEmail1">აღწერა</label>
                                        <textarea rows="6" type="text" class="form-control" id="description" name="description">

                            </textarea>
                                    </div> -->

                            <div class="form-group col-12 col-md-6 mt-5">
                                <button class="btn btn-primary" type='submit'>დამატება</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="project_details" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog bd-example-modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">პროექტის დეტალები</h5>
                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">დარჩენილი რეზერვი</h5>
                                <span class="comma">{{ $project->start_reserve - $project->reserve}}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">დაკავებული რეზერვი</h5>
                                <span class="comma">{{$project->reserve}}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-green">
                                <h5 class="project-dashboard-card">რეზერვის უძრავი ქონებით ნარჩენი</h5>
                                <span class="comma">{{ $udzraviReserve }}</span>

                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card ">რეზერვის ქეშით ნარჩენი</h5>
                                <span class="comma">{{ $cashReserve }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">დაუკავებელი ავანსი</h5>
                                <span class="comma">{{ $project->avance }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card ">დაკავებული ავანსი</h5>
                                <span class="comma">{{ $project->start_avance - $project->avance }}₾</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">ავანსის უძრავი ქონებით ნარჩენი</h5>
                                <span class="comma">{{ $udzraviAvance }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">ავანსის ქეშით ნარჩენი</h5>
                                <span class="comma">{{ $cashAvance }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card color-red">
                                <h5 class="project-dashboard-card">გამონთავისუფლებული</h5>
                                <span class="comma">{{ $free }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="card plane-card detail-card" data-toggle="modal" data-target="#project_details">
                                <h5 class="project-dashboard-card"> გამონთავისუფლებული ჩარიცხული</h5>
                                <span class="comma">{{$bankProjectPlane}}</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @foreach ($projectPlanes as $projectPlane)
        @php
            if ($project->avance > 0) {
                $maxAvancePercent = 100 / ($projectPlane->price / $project->avance);
            } else {
                $maxAvancePercent = 0;
            }
            if ($maxAvancePercent > 100) {
                $maxAvancePercent = 100;
            } elseif ($maxAvancePercent < 0) {
                $maxAvancePercent = 0;
        } @endphp
        <div class="modal fade bd-example-modal-lg" id="edit_plane_{{ $projectPlane->id }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">გეგმის რედაქტირება </h5>
                        <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action='{{ route('update.project_plane', $projectPlane->id) }}' method='post'>
                            @csrf
                            @method('PUT')
                            <div class='row'>
                                <div class="form-group col-12 col-md-6 mt-3">
                                    <label for="exampleInputEmail1">დასახელება</label>
                                    <input type="text" value="{{ $projectPlane->name }}" name='name' required
                                        class="form-control mt-2">
                                </div>
                                <div class="form-group col-12 col-md-6 mt-3">
                                    <label for="exampleInputEmail1">ანაზღაურება</label>
                                    <input type="text" value="{{ $projectPlane->price }}" step="0.01"
                                        name='price' required class="form-control mt-2 price_{{ $projectPlane->id }}">
                                </div>

                                @if ($project->reserve < $project->start_reserve)
                                    <div class="form-group col-12 col-md-4 mt-3">
                                        <label for="exampleInputEmail1">რეზერვის %</label>
                                        <input type="number" id="reserve_price" step="0.01" name='reserve_percent'
                                            required min='0' max='100'
                                            value="{{ $projectPlane->reserve_percent != null ? $projectPlane->reserve_percent : '0' }}"
                                            class="form-control mt-2 reserve_percent_{{ $projectPlane->id }}">
                                    </div>
                                @else
                                    <div class="form-group col-12 col-md-4 mt-3">
                                        <label for="exampleInputEmail1">რეზერვის %</label>
                                        <input type="number" id="reserve_price" step="0.01" name='reserve_percent'
                                            disabled placeholder="რეზერვი ამოიწურა" min='0' max='100'
                                            value="{{ $projectPlane->reserve_percent }}"
                                            class="form-control mt-2 reserve_percent_{{ $projectPlane->id }}">
                                    </div>
                                @endif
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">რეზერვის თანხა</label>
                                    <input type="number" value="{{ $projectPlane->reserve_price }}" step="0.01"
                                        name='reserve_price'
                                        class="form-control reserve_price_{{ $projectPlane->id }} mt-2">
                                </div>
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <button type="button" onclick="calculateEditReserve({{ $projectPlane->id }})"
                                        class="btn btn-primary calc-btn">დათვლა</button>
                                </div>
                                @if ($project->avance > 0)
                                    <div class="form-group col-12 col-md-4 mt-3">
                                        <label for="exampleInputEmail1">ავანსის პროცენტი</label>
                                        <input type="number" id="avance_price" step="0.01" name='avance_percent'
                                            required min='0' max='100'
                                            value="{{ $projectPlane->avance_percent }}"
                                            class="form-control mt-2 avance_percent_{{ $projectPlane->id }}">
                                    </div>
                                @else
                                    <div class="form-group col-12 col-md-4 mt-3">
                                        <label for="exampleInputEmail1">ავანსის %</label>
                                        <input type="text" id="avance_percent" name='avance_percent' disabled
                                            placeholder="პროექტის ავანსი ამოიწურა"
                                            class="form-control mt-2 avance_percent_{{ $projectPlane->id }}">
                                    </div>
                                @endif
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <label for="exampleInputEmail1">ავანსის თანხა</label>
                                    <input type="number" value="{{ $projectPlane->avance_price }}" step="0.01"
                                        name='avance_price'
                                        class="form-control avance_price_{{ $projectPlane->id }} mt-2">
                                </div>
                                <div class="form-group col-12 col-md-4 mt-3">
                                    <button type="button" onclick="calculateEditPrice({{ $projectPlane->id }})"
                                        class="btn btn-primary calc-btn">დათვლა</button>
                                </div>

                                <!-- <div class="form-group col-12 col-md-6 mt-3">
                                        <label for="exampleInputEmail1">შესრულების ვადა</label>
                                        <input type="date" value="{{ $projectPlane->deadline_date }}" required name='deadline_date'
                                            class="form-control mt-2">
                                    </div> -->

                                <div class="form-group col-12 col-md-6 mt-3">
                                    <label for="exampleInputEmail1">ანაზღაურების თარიღი</label>
                                    <input type="date" value="{{ $projectPlane->pay_date }}" required name='pay_date'
                                        class="form-control mt-2">
                                </div>
                                <div class="form-group col-12 col-md-6 mt-3">
                                    <label for="exampleInputEmail1">სტატუსი</label>
                                    <select class="form-select" name='status' aria-label="Default select example">
                                        @if ($projectPlane->status == 1)
                                            <option value="1">აქტიური</option>
                                        @elseif($projectPlane->status == null)
                                            <option value="">დასრულებული</option>
                                        @endif
                                        <option value="">დასრულებული</option>
                                        <option value="1">აქტიური</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group col-12 col-md-12 mt-3">
                                        <label for="exampleInputEmail1">აღწერა</label>
                                        <textarea class="form-control w-100" name="description">
                                {{ $projectPlane->description }}
                            </textarea>
                                    </div> -->

                                <div class="form-group col-12 col-md-6 mt-5">
                                    <button class="btn btn-primary" type='submit'>რედაქტირება</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role == 1) 
        
        <div class="modal fade bd-example-modal-lg" id="edit_guarantee_status_{{ $projectPlane->id }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">გარანტიის სტატუსის რედაქტირება </h5>
                        <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action='{{ route('update.guarantee_status', $projectPlane->id) }}' method='post'>
                            @csrf
                            @method('PUT')
                            <div class='row'>
                                <div class="form-group col-12 col-md-12 mt-3">
                                    <label for="exampleInputEmail1">სტატუსი</label>
                                    <select class="form-select" name='guarantee_status'
                                        aria-label="Default select example">
                                        @if ($projectPlane->guarantee_status == 1)
                                            <option value="1">ჩარიცხული</option>
                                            <option value="0">ჩასარიცხი</option>
                                        @elseif($projectPlane->guarantee_status == 0)
                                            <option value="0">ჩასარიცხი</option>
                                            <option value="1">ჩარიცხული</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-12 mt-5">
                                    <button class="btn btn-primary" type='submit'>რედაქტირება</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="modal fade bd-example-modal-lg" id="delete_{{ $projectPlane->id }}" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">პროექტის წაშლა</h5>
                        <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('delete.project_plane', $projectPlane->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">წაშლა</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <script>
        function calculatePrice() {
            if ($(".reserve_price").val() == null) {
                alert("ავანსის ფასის დასათვლელად გთხოვთ პირველ ეტაპზე დაითვალოთ რეზერვის თანხა")
            } else {
                if ($('.price').val()) {
                    $(".avance_price").empty();
                    var percent = parseFloat($('.avance_percent').val())
                    var price = parseFloat($('.price').val())
                    var value = (price - $(".reserve_price").val()) / 100 * percent
                    $(".avance_price").val(value.toFixed(2))
                } else {
                    alert("შეიყვანეთ ანაზღაურება")
                }
            }


        }

        function calculateEditPrice($id) {


            if ($(".reserve_price_" + $id).val() == null) {
                alert("ავანსის ფასის დასათვლელად გთხოვთ პირველ ეტაპზე დაითვალოთ რეზერვის თანხა")
            } else {
                $(".avance_price_" + $id).empty();
                var percent = parseFloat($('.avance_percent_' + $id).val())
                var price = parseFloat($('.price_' + $id).val())
                var value = (price - $(".reserve_price_" + $id).val()) / 100 * percent
                $(".avance_price_" + $id).val(value.toFixed(2))
            }
        }

        function calculateReserve() {
            if ($('.price').val()) {
                $(".reserve_price").empty();
                var percent = parseFloat($('.reserve_percent').val())
                var price = parseFloat($('.price').val())
                var value = price / 100 * percent
                $(".reserve_price").val(value.toFixed(2))
            } else {
                alert("შეიყვანეთ ანაზღაურება")
            }
        }

        function calculateEditReserve($id) {
            $(".reserve_price_" + $id).empty();
            var percent = parseFloat($('.reserve_percent_' + $id).val())
            var price = parseFloat($('.price_' + $id).val())
            var value = price / 100 * percent
            $(".reserve_price_" + $id).val(value.toFixed(2))
        }
    </script>

@stop
