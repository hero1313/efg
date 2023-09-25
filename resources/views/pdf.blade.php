<!DOCTYPE html>
<html lang="ka">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">


<title>Certificate</title>
<meta charset="utf-8" />

</head>
<body style="font-family: 'DejaVu Sans', sans-serif;">
@php
$planePay = DB::table('project_plans')
->where('project_id', '=', $project->id)
->where('status', '=', null)
->sum('pay');
$planePrice = DB::table('project_plans')
->where('project_id', '=', $project->id)
->where('status', '=', null)
->sum('price');
@endphp
<table class="table">
  <tbody>
    <tr>
      <th scope="row">პროექტის დასახელება</th>
      <th scope="row">{{ $project->name }}</th>
    </tr>
  </tbody>
</table>
<br>
<br>
<table class="table">
  <thead>
    <tr>
      <th scope="col">დასახელება (ქართ)</th>
      <th scope="col">Name (ENG) </th>
      <th scope="col">თანხა</th>

    </tr>
  </thead>
  <tbody>
    <tr>
      <td>კონტრაქტის ღირებულება</td>
      <td>contract value</td>
      <td>{{ $project->price }}</td>
    </tr>
    <tr>
      <td>სულ შესრულებულ სამუშაოთა ღირებულება</td>
      <td>Total cost of completed works</td>
      <td>{{ $planePrice }}</td>
    </tr>
    <tr>
      <td>სულ გადახდილი თანხა</td>
      <td>Total amount paid</td>
      <td>{{ $planePay }}</td>
    </tr>
    <tr>
      <td>გადახდილი ავანსი</td>
      <td>advance paid</td>
      <td>{{ $project->start_avance }}</td>
    </tr>
    <tr>
      <td>სულ დაკავებული საავანსო თანხა</td>
      <td>Total seized down payment amount</td>
      <td>{{$project->start_avance - $project->avance }}</td>
    </tr>
    <tr>
      <td>სარეზერვო თანხა</td>
      <td>reserve amount</td>
      <td>{{ $project->start_reserve }}</td>
    </tr>
    <tr>
      <td>სულ დაკავებული სარეზერვო თანხა</td>
      <td>Total reserve amount held</td>
      <td>{{$project->start_reserve - $project->reserve }}</td>
    </tr>
    <br>
    <hr>
    <tr>
      <td>სამუშაოთა ღირებულება ამ სერთიფიკატით</td>
      <td>Cost of work with this certificate</td>
      <td>{{ $plane->price }}</td>
    </tr>
    <tr>
      <td>ავანსის დაკავება</td>
      <td>withholding of advance</td>
      <td>{{ $plane->avance_price }}</td>
    </tr>
    <tr>
      <td>სარეზერვო თანხის დაკავება</td>
      <td>Detention of reserve funds</td>
      <td>{{ $plane->reserve_price }}</td>
    </tr>
    <tr>
      <td>გადასახდელი თანხა ამ სერთიფიკატით</td>
      <td>Amount payable under this certificate</td>
      <td>{{ $plane->pay }}</td>
    </tr>
  </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>
</html>

