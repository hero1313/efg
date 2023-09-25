<?php

namespace App\Http\Controllers;

use App\Models\Guarantee;
use App\Models\Project;
use App\Models\ProjectPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use File;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = '';
        if($request->search){
            $search = $request->search;
        }
        if(!$search){
            $projects = Project::all();
        }
        else{
            $projects = Project::where('name', 'LIKE', "%{$search}%")->get();
        }

        $guarantees = Guarantee::all();
        return view('components.projects', compact(['projects', 'guarantees', 'search']));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = new Project;
        $project->user_id = Auth::user()->id;
        $project->name = $request->name;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->guarantee_id = $request->guarantee_id;
        $project->price = $request->price;
        if($request->avance_percent && $request->avance_price){
            $project->avance_percent = 100 / ($request->price / $request->avance_price);
            $project->avance = $request->avance_price;
        }
        if($request->avance_percent && !$request->avance_price){
            $project->avance = $request->price / 100 * $request->avance_percent;
            $project->avance_percent = $request->avance_percent;

        }
        elseif($request->avance_price && !$request->avance_percent ){
            $project->avance_percent = 100 / ($request->price / $request->avance_price);
            $project->avance = $request->avance_price;
        }

        $project->start_avance = $project->avance;
        $project->reserve_percent = $request->reserve_percent;
        $project->start_reserve = $project->price / 100 * $project->reserve_percent;
        $project->reserve = 0;
        $project->remaining_tax = $request->price - $project->avance;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('assets/image/', $filename);
            $project->image = "$filename";
        }
        $project->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $today = Carbon::now();
        $project = Project::find($id);
        $cashReserve = Guarantee::where('project_id',$id)->where('type',2)->get()->sum('price');
    
        $udzraviReserve = Guarantee::where('project_id',$id)->where('type',2)->get()->sum('reserve_price');
        
        $cashAvance = Guarantee::where('project_id',$id)->where('type',1)->get()->sum('price'); 
        $udzraviAvance = Guarantee::where('project_id',$id)->where('type',1)->get()->sum('reserve_price');
        $cashReserve2 = Guarantee::where('project_id',$id)->where('type',2)->where('deadline','<',$today)->get()->sum('price');
        $udzraviReserve2 = Guarantee::where('project_id',$id)->where('type',2)->where('deadline','<',$today)->get()->sum('reserve_price');

        $avanceFree = Guarantee::where('project_id',$id)->where('type',1)->get()->sum('start_price');
        $avanceFree2 = Guarantee::where('project_id',$id)->where('type',1)->get()->sum('start_reserve_price');
        $reserve = Guarantee::where('project_id',$id)->where('type',2)->where('deadline','<',$today)->get()->sum('start_price');
        $reserve2 = Guarantee::where('project_id',$id)->where('type',2)->where('deadline','<',$today)->where('type',2)->get()->sum('start_reserve_price');
        $doneProjectPlane = ProjectPlan::where('project_id', $id)->where('status','=',null)->get()->sum('price'); 
        $free =($avanceFree + $avanceFree2 + $reserve + $reserve2) - ($udzraviAvance + $cashAvance + $udzraviReserve2 + $cashReserve2);
        $bankProjectPlane = ProjectPlan::where('project_id', $id)->where('status','=',null)->where('guarantee_status','=', 1)->get()->sum('price'); 

        // dd($udzraviAvance + $cashAvance + $udzraviReserve2 + $cashReserve2);

        if($doneProjectPlane){
            $done = $doneProjectPlane;
        }
        else{
            $done = 0;
        }
        if($project == null){
            abort(404, 'Page not found');
        }
        else{
            $remaining = $project->price - $done;
        }
        $projectPlanes = ProjectPlan::where('project_id', $id)->get();
        
        return view('components.project', compact(['project','bankProjectPlane', 'projectPlanes', 'done', 'cashAvance', 'cashReserve', 'udzraviAvance', 'udzraviReserve', 'remaining', 'free']));

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        $doneProjectPlane = ProjectPlan::where('project_id', $id)->get(); 
        $project->name = $request->name;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->guarantee_id = $request->guarantee_id;
        $project->price = $request->price;
        $project->avance = $request->avance;
        $project->start_avance = $request->avance;
        // $project->avance_percent = $request->avance_percent;
        $project->reserve_percent = $request->reserve_percent;
        $project->start_reserve = $project->price / 100 * $request->reserve_percent;

        if ($request->hasfile('image')) {
            $destination = 'assets/image/' . $project->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('assets/image/', $filename);
            $project->fimage = "$filename";
        }

        $project->update();
        if($project->status = 0){
            foreach($doneProjectPlane as $plane){
                $plane->project->status = 0;
                $plane->update();
            }
        }
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $destination = 'assets/image/' . $project->image;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        $project->delete();
        $doneProjectPlane = ProjectPlan::where('project_id', $id)->get(); 

        foreach($doneProjectPlane as $plane){
            $plane->delete();
        }

        return redirect()->back();

    }


    public function projectForecast(Request $request)
    {
        $search = '';
        $uncheckedProjects = Project::where('forecast_index', null)->get();
        $checkedProjects = Project::where('forecast_index', 1)->get();

        return view('components.forecast', compact(['checkedProjects','uncheckedProjects', 'search']));

    }

    public function updateForecast(Request $request)
    {
        $projects = Project::all();
        foreach($projects  as $project){
            $project->forecast_gamontavisuflebuli = $request->gamontavisuflebuli[$project->id];
            $project->forecast_calculate = $request->datvlili[$project->id];
            $project->forecast_plane = $request->forecast_plane[$project->id];
            if(isset($request->forecast_index[$project->id])){
            $project->forecast_index = $request->forecast_index[$project->id];
            }
            else{
                $project->forecast_index = null;
            }
            $project->update();

        }

        return redirect()->back();

    }
}