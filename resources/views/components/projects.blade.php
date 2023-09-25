@extends('index')
@section('content')
@if(Auth::user()->role == 1) 
    <button class='btn btn-primary add-project' data-toggle="modal" data-target="#add-porject">პროექტის დამატება</button>
@endif
<div class="container-fluid flex-grow-1 container-p-y">

    <form action="/projects" method="get">
        <div class="form-group d-flex mt-3 mb-3 search-div">
            <input type="text" name='search' class="form-control mt-2 search" value="{{$search}}" placeholder="მოძებნე პროექტი">
            <button class="btn btn-primary">ძებნა</button>
        </div>
    </form>
    <div class="card mt-5">
        <h5 class="card-header">პროექტები</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped project-table">
                <thead>
                    <tr>
                        <th>რედაქტირება</th>
                        <th>წაშლა</th>
                        <th>სტატუსი</th>
                        <th>დასახელება</th>
                        <th>რეზერვის პროცენტი</th>
                        <th>სარეზერვო თანხა</th>
                        <th>დაუკავებელი რეზერვი</th>
                        <th>დაკავებული რეზერვი</th>
                        <th>ავანსის პროცენტი</th>
                        <th>დაუკავებელი ავანსი</th>
                        <th>დაკავებული ავანსი</th>
                        <th>გადახდილი ავანსი</th>
                        <th>სერთიფიკატიდან გადახდილი</th>
                        <th>ჯამურად გადახდილი თანხა</th>
                        <th>დარჩენილი გადასახადი</th>
                        <th>შესრულებული სამუშაო</th>
                        <th>დარჩენილი სამუშაო</th>
                        <th>ღირებულება</th>
                        <th>დაწყება</th>
                        <th>დასრულება</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                    $planePais = DB::table('project_plans')
                    ->where('status', '=', null)
                    ->sum('pay');
                    @endphp
                    @foreach($projects as $project)
                    @php
                    $planePay = DB::table('project_plans')
                    ->where('project_id', '=', $project->id)
                    ->where('status', '=', null)
                    ->sum('pay');

                    $doneProjectPlane = DB::table('project_plans')->where('project_id', $project->id)->where('status','=',null)->get()->sum('price'); 
                    $remaining = $project->price - $doneProjectPlane;

                    @endphp
                    <tr style="cursor: pointer;">
                        <td>
                            @if(Auth::user()->role == 1) 
                            <button class='btn btn-primary' data-toggle="modal" data-target="#edit_project_{{$project->id}}">რედაქტირება</button>
                            @endif
                        </td>
                        <td>
                            @if(Auth::user()->role == 1) 
                            <button class='btn btn-danger' data-toggle="modal" data-toggle="modal" data-target="#delete_{{$project->id}}">წაშლა</button>
                            @endif
                        </td>
                        <td>
                            @if($project->status == 1)
                            <button class='btn btn-success'>აქტიური</button>
                            @elseif($project->status == 0)
                            <button class='btn btn-danger'>დასრულებული</button>
                            @endif
                        </td>
                        <td class="edit pro-name " href="{{route('show.project',$project->id)}}">{{$project->name}}</td>
                        <td class="edit" href="{{route('show.project',$project->id)}}">{{$project->reserve_percent}}%</td>
                        <td class="edit comma" href="{{route('show.project',$project->id)}}" class="comma">{{$project->start_reserve}} ₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->start_reserve - $project->reserve}} ₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->reserve}} ₾</td>
                        <td class="edit" href="{{route('show.project',$project->id)}}">{{$project->avance_percent}}%</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->avance}}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->start_avance - $project->avance}}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->start_avance}}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$planePay}}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$planePay + $project->start_avance}}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->price - ($planePay + $project->start_avance) }}₾</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$doneProjectPlane}}</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$remaining}}</td>
                        <td class="comma edit" href="{{route('show.project',$project->id)}}">{{$project->price}} ₾</td>
                        <td class="edit" href="{{route('show.project',$project->id)}}">{{$project->start_date}}</td>
                        <td class="edit" href="{{route('show.project',$project->id)}}">{{$project->end_date}}</td>
                    </tr>
                    
                    <div class="modal fade bd-example-modal-lg" id="edit_project_{{$project->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">გეგმის დამატება</h5>
                                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action='{{route("update.project",$project->id)}}' method='post'>
                                        @csrf
                                        @method('PUT')
                                        <div class='row'>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">დასახელება</label>
                                                <input type="text" required value="{{$project->name}}" name='name' required class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">ფოტო</label>
                                                <input type="file" value="{{$project->image}}" name='image' class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">დაწყების თარიღი</label>
                                                <input type="date" required value="{{$project->start_date}}" name='start_date' required class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">დასრულების თარიღი</label>
                                                <input type="date" required value="{{$project->end_date}}" name='end_date' class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">ჯამური ღირებულება</label>
                                                <input type="number" required name='price' step="0.01" value="{{$project->price}}" class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">ავანსის ღირებულება</label>
                                                <input type="number" required name='avance' step="0.01" value="{{$project->avance}}" class="form-control mt-2">
                                            </div>
                                            <!-- <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">ავანსის პროცენტი</label>
                                                <input type="number" min='0' max='100' name='avance_percent'
                                                    value="{{$project->avance_percent}}" required
                                                    class="form-control mt-2">
                                            </div> -->
                                            <div class="form-group col-12 col-md-6 mt-3">
                                                <label for="exampleInputEmail1">რეზერვის პროცენტი</label>
                                                <input type="number" min='0' max='100' name='reserve_percent' value="{{$project->reserve_percent}}" required class="form-control mt-2">
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-2">
                                                <label for="exampleInputEmail1">სტატუსი</label>

                                                <select class="form-select mt-3" name='status' aria-label="Default select example">
                                                    @if($project->status == 1)
                                                    <option value="1">აქტიური</option>
                                                    @elseif($project->status == null)
                                                    <option value="">დასრულებული</option>
                                                    @endif
                                                    <option value="">დასრულებული</option>
                                                    <option value="1">აქტიური</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-md-6 mt-5">
                                                <button class="btn btn-primary" type='submit'>დამატება</button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <tr class="bottom-sticky" style="cursor: pointer;">
                        <td class="pro-name bold" style="z-index: 4;">ჯამი
                        </td>
                        <td>
                        </td>
                        <td></td>
                        <td class="pro-name bold"></td>
                        <td></td>
                        <td class="edit comma bold"  >{{$projects->sum('start_reserve')}} </td>
                        <td class="comma edit bold" >{{$projects->sum('start_reserve') - $projects->sum('reserve')}} </td>
                        <td class="comma edit bold" >{{$projects->sum('reserve')}}</td>
                        <td></td>
                        <td class="comma edit bold" >{{$projects->sum('avance')}}</td>
                        <td class="comma edit bold" >{{$projects->sum('start_avance') - $projects->sum('avance')}}</td>
                        <td class="comma edit bold" >{{$projects->sum('start_avance')}}</td>
                        <td class="comma edit bold" >{{$planePais}}</td>
                        <td class="comma edit bold" >{{$planePais + $projects->sum('start_avance')}}</td>
                        <td class="comma edit bold" >{{$projects->sum('price') - ($planePais + $projects->sum('start_avance')) }}</td>
                        <td></td>
                        <td></td>
                        <td class="comma edit bold" >{{$projects->sum('price')}}</td>
                        <td></td>
                        <td></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($projects as $project)

<div class="modal fade bd-example-modal-lg" id="delete_{{$project->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">პროექტის წაშლა</h5>
                <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('delete.project', $project->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">წაშლა</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade bd-example-modal-lg" id="add-porject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">პროექტის დამატება</h5>
                <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action='{{route("store.project")}}' method='post'>
                    @csrf
                    <div class='row'>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">დასახელება</label>
                            <input type="text" name='name' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ფოტო</label>
                            <input type="file" name='image' class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">დაწყების თარიღი</label>
                            <input type="date" name='start_date' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">დასრულების თარიღი</label>
                            <input type="date" name='end_date' required class="form-control mt-2">
                        </div>

                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ჯამური ღირებულება</label>
                            <input type="number" name='price' step="0.01" required class="form-control mt-2 price">
                        </div>
                        <div class="form-group col-12 col-md-4 mt-3">
                            <label for="exampleInputEmail1">ავანსის %</label>
                            <input type="number" id="avance_percent" name='avance_percent' required min='0' max='100' class="form-control mt-2 avance_percent">
                        </div>
                        <div class="form-group col-12 col-md-4 mt-3">
                            <label for="exampleInputEmail1">ავანსის თანხა</label>
                            <input type="number" name='avance_price' step="0.01" class="form-control avance_price mt-2">
                        </div>
                        <div class="form-group col-12 col-md-4 mt-3">
                            <button type="button" onclick="calculatePrice()" class="btn btn-primary calc-btn">დათვლა</button>
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">რეზერვის პროცენტი</label>
                            <input type="number" min='0' max='100' name='reserve_percent' required class="form-control mt-2">
                        </div>

                        <div class="form-group col-12 col-md-6 mt-5">
                            <button class="btn btn-primary" type='submit'>დამატება</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
<!-- Button trigger modal -->
<script>
    function calculatePrice() {

        if ($('.price').val()) {
            $(".avance_price").empty();
            var percent = parseFloat($('.avance_percent').val())
            var price = parseFloat($('.price').val())
            var value = price / 100 * percent
            $(".avance_price").val(value.toFixed(2))
        } else {
            alert("შეიყვანეთ ანაზღაურება")
        }
    }
    $(document).ready(function() {
        $('.edit').click(function() {
            window.location = $(this).attr('href');
            return false;
        });
    });
</script>
@stop