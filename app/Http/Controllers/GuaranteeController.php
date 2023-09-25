<?php

namespace App\Http\Controllers;

use App\Models\Guarantee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuaranteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limitDate = Carbon::now()->addMonth();
        $today = Carbon::now();
        $search = '';
        if($request->search){
            $search = $request->search;
        }
        if(!$search){
            $guarantees = Guarantee::all();
        }
        else{
            $guarantees = Guarantee::where('number', 'LIKE', "%{$search}%")->get();
        }
        $projects = Project::all();
        return view('components.guarantees', compact(['guarantees','projects','limitDate','today','search']));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $guarantee = new Guarantee;
        $guarantee->number = $request->number;
        $guarantee->bank = $request->bank;
        $guarantee->price = $request->price;
        $guarantee->start_price = $request->price;
        $guarantee->reserve_price = $request->reserve_price;
        $guarantee->start_reserve_price = $request->reserve_price;
        $guarantee->release_date = $request->release_date;
        $guarantee->deadline = $request->deadline;
        $guarantee->type = $request->type;
        $guarantee->project_id = $request->project_id;
        $guarantee->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guarantee = Guarantee::find($id);
        $guarantee->number = $request->number;
        $guarantee->bank = $request->bank;
        $guarantee->price = $request->price;
        $guarantee->start_price = $request->price;
        $guarantee->reserve_price = $request->reserve_price;
        $guarantee->start_reserve_price = $request->reserve_price;
        $guarantee->release_date = $request->release_date;
        $guarantee->deadline = $request->deadline;
        $guarantee->type = $request->type;
        $guarantee->status = $request->status;
        $guarantee->update();
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guarantee = Guarantee::find($id);
        $guarantee->delete();
        return redirect()->back();

    }
}
