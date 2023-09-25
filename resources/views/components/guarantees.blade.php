@extends('index')
@section('content')
@if(Auth::user()->role == 1) 
<button class='btn btn-primary add-project' data-toggle="modal" data-target="#add_guarantee">გარანტიის დამატება</button>
@endif

<div class="container-fluid flex-grow-1 container-p-y">
    <form action="/guarantees" method="get">
        <div class="form-group d-flex mt-3 mb-3 search-div">
            <input type="text" name='search' class="form-control mt-2 search" value="{{ $search }}" placeholder="მოძებნე პროექტი">
            <button class="btn btn-primary">ძებნა</button>
        </div>
    </form>
    <div class="card mt-5">
        <h5 class="card-header">გარანტიები</h5>
        <div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>რედაქტირება</th>
                            <th>წაშლა</th>
                            <th>სტატუსი</th>
                            <th>ტიპი</th>
                            <th>პროექტი</th>
                            <th>ნომერი</th>
                            <th>ბანკი</th>
                            <th>გარანტიის ღირებულება</th>
                            <th>გამონთავისუფლებული</th>
                            <th>ქეშით უზრუნველყოფილი საწყისი </th>
                            <th>ქეშით უზრუნველყოფილი დარჩენილი </th>
                            <th>ქონებით უზრუნველყოფილი საწყისი </th>
                            <th>ქონებით უზრუნველყოფილი დარჩენილი </th>
                            <th>შექმნის თარიღი</th>
                            <th>გარანტიის ვადა</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($guarantees as $guarantee)
                        @php
                        $project = DB::table('projects')
                        ->where('id', '=', $guarantee->project_id)
                        ->first();
                        @endphp
                        <tr>
                            <td>
                                @if(Auth::user()->role == 1) 
                                    <button class='btn btn-primary' data-toggle="modal" data-target="#edit_guarantee_{{ $guarantee->id }}">რედაქტირება</button>
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->role == 1) 
                                    <button class='btn btn-danger' data-toggle="modal" data-target="#delete_guarantee_{{ $guarantee->id }}">წაშლა</button>
                                @endif
                            </td>
                            <td>
                                @if ($guarantee->deadline < $limitDate) <button class='btn btn-warning'>ვადა გასდის</button>
                                    @elseif($guarantee->deadline < $today) <button class='btn btn-danger'>დასრულებული</button>
                                        @elseif($guarantee->deadline > $today)
                                        <button class='btn btn-success'>აქტიური</button>
                                        @endif
                            </td>
                            <td>
                                @if ($guarantee->type == 2)
                                <button class='btn btn-success'>რეზერვის გარანტია</button>
                                @elseif($guarantee->type == 1)
                                <button class='btn btn-primary'>ავანსის გარანტია</button>
                                @endif
                            </td>
                            @if ($project)
                            <td class="pro-name">{{ $project->name }}</td>
                            @else
                            <td class="pro-name">არარის არჩეული</td>
                            @endif
                            <td>{{ $guarantee->number }}</td>
                            <td>{{ $guarantee->bank }}</td>
                            <td class="comma">{{ $guarantee->start_price + $guarantee->start_reserve_price }}</td>
                            <td class="comm">
                                @if ($guarantee->type == 2 && $guarantee->deadline < $today) 
                                {{ $guarantee->start_price + $guarantee->start_reserve_price }} 
                                @elseif($guarantee->type == 1)
                                    {{ $guarantee->start_price + $guarantee->start_reserve_price - $guarantee->price - $guarantee->reserve_price }}
                                @endif
                            </td>
                            <td class="comma">{{ $guarantee->start_price }}</td>
                            <td class="comma">{{ $guarantee->price }}</td>
                            <td class="comma">{{ $guarantee->start_reserve_price }}</td>
                            <td class="comma">{{ $guarantee->reserve_price }}</td>
                            <td>{{ $guarantee->release_date }}</td>
                            <td>{{ $guarantee->deadline }}</td>
                        </tr>
                        @endforeach
                        <tr class="bottom-sticky" style="cursor: pointer;">
                            <td class="pro-name bold" style="z-index: 4;">ჯამი
                            </td>
                            <td>
                            </td>
                            <td></td>
                            <td style="z-index: 7;" class=" pro-name bold"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="comma edit bold">{{$guarantees->sum('start_price') + $guarantees->sum('start_reserve_price')}}</td>
                            <td></td>
                            <td class="comma edit bold">{{$guarantees->sum('start_price')}}</td>
                            <td class="comma edit bold">{{$guarantees->sum('price')}}</td>
                            <td class="comma edit bold">{{$guarantees->sum('start_reserve_price')}}</td>
                            <td class="comma edit bold">{{$guarantees->sum('reserve_price')}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="add_guarantee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">გეგმის დამატება</h5>
                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action='{{ route("store.guarantee") }}' method='post'>
                        @csrf
                        <div class='row'>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">პროექტი</label>
                                <select class="form-select mt-2" name="project_id" aria-label="Default select example">
                                    @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ნომერი</label>
                                <input type="text" name='number' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ბანკი</label>
                                <input type="text" name='bank' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ქეშით უზრუნველყოფილი</label>
                                <input type="number" step="0.01" name='price' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">ქონებით უზრუნველყოფილი</label>
                                <input type="number" step="0.01" name='reserve_price' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">შექმნის თარიღი</label>
                                <input type="date" name='release_date' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">გარანტიის ვადა</label>
                                <input type="date" name='deadline' required class="form-control mt-2">
                            </div>
                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="exampleInputEmail1">გარანტიის ტიპი</label>
                                <select class="form-select mt-2" name="type" aria-label="Default select example">
                                    <option value="1">ავანსის გარანტია</option>
                                    <option value="2">რეზერვის გარანტია</option>
                                </select>
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

    @foreach ($guarantees as $guarantee)
    @php
    $projecte = DB::table('projects')
    ->where('id', '=', $guarantee->project_id)
    ->first();
    @endphp

    <div class="modal fade bd-example-modal-lg" id="edit_guarantee_{{ $guarantee->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">გეგმის დამატება</h5>
                    <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action='{{ route('update.guarantee', $guarantee->id) }}' method='post'>
                        @csrf
                        @method('PUT')
                        <div class='row'>
                            {{-- <div class="form-group col-12 col-md-6 mt-3">
                                        <label for="exampleInputEmail1">პროექტი</label>
                                        <select class="form-select mt-2" name="project_id"
                                            aria-label="Default select example">
                                            @if ($projecte)
                                                <option value="{{$projecte->id}}">{{$projecte->name}}</option>
                            @else
                            @endif
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ნომერი</label>
                            <input type="text" value="{{ $guarantee->number }}" name='number' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ბანკი</label>
                            <input type="text" value="{{ $guarantee->bank }}" name='bank' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ქეშით უზრუნველყოფილი</label>
                            <input type="text" value="{{ $guarantee->price }}" step="0.01" name='price' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">ქონებით უზრუნველყოფილი</label>
                            <input type="number" step="0.01" name='reserve_price' value="{{ $guarantee->reserve_price }}" required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">შექმნის თარიღი</label>
                            <input type="text" value="{{ $guarantee->release_date }}" name='release_date' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">გარანტიის ვადა</label>
                            <input type="text" value="{{ $guarantee->deadline }}" name='deadline' required class="form-control mt-2">
                        </div>
                        <div class="form-group col-12 col-md-6 mt-3">
                            <label for="exampleInputEmail1">გარანტიის ტიპი</label>
                            <select class="form-select mt-2" name='type' aria-label="Default select example">
                                @if ($guarantee->type == 2)
                                <option value="2">რეზერვის გარანტია</option>
                                @elseif($guarantee->type == 1)
                                <option value="1">ავანსის გარანტია</option>
                                @endif
                                <option value="1">ავანსის გარანტია</option>
                                <option value="2">რეზერვის გარანტია</option>
                            </select>
                        </div>
                        <!-- <div class="form-group col-12 col-md-6 mt-3">
                                    <label for="exampleInputEmail1">გარანტიის სტატუსი</label>
                                    <select class="form-select mt-2" name='type' aria-label="Default select example">
                                        @if ($guarantee->status == 1)
                                        <option value="1">აქტიური</option>
                                        @elseif($guarantee->status == null)
                                        <option value="">დასრულებული</option>
                                        @endif
                                        <option value="">დასრულებული</option>
                                        <option value="1">აქტიური</option>
                                    </select>
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

<div class="modal fade bd-example-modal-lg" id="delete_guarantee_{{ $guarantee->id }}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ნამდვილად გსურთ გარანტიის წაშლა</h5>
                <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('delete.guarantee', $guarantee->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">წაშლა</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach
@stop