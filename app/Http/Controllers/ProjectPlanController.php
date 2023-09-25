<?php

namespace App\Http\Controllers;

use App\Models\Guarantee;
use App\Models\ProjectPlan;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use File;


class ProjectPlanController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $projectPlane = new ProjectPlan;
        $projectPlane->project_id = $id;
        $projectPlane->name = $request->name;
        $projectPlane->price = $request->price;
        $projectPlane->pay_date = $request->pay_date;
        $projectPlane->deadline_date = $request->deadline_date;
        $projectPlane->description = $request->description;
        $projectPlane->avance_price = $request->avance_price;
        $projectPlane->reserve_price = $request->reserve_price;
        if ($request->reserve_price > 0) {
            $projectPlane->reserve_percent = 100 / ($request->price / $request->reserve_price);
        } else {
            $projectPlane->reserve_percent == 0;
        }
        $projectPlane->reserve_price = $request->reserve_price;
        if ($request->avance_price > 0) {
            $projectPlane->avance_percent = 100 / (($request->price - $request->reserve_price) / $request->avance_price);
        } else {
            $projectPlane->avance_percent == 0;
        }
        $projectPlane->status = 1;
        $projectPlane->pay = $projectPlane->price - $request->avance_price - $request->reserve_price;
        $projectPlane->save();
        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $projectPlane = ProjectPlan::find($id);
        $projectPlane->name = $request->name;
        $projectPlane->price = $request->price;
        $projectPlane->pay_date = $request->pay_date;
        $projectPlane->deadline_date = $request->deadline_date;
        $projectPlane->description = $request->description;
        $projectPlane->avance_price = $request->avance_price;
        $projectPlane->reserve_price = $request->reserve_price;
        if ($request->reserve_price > 0) {
            $projectPlane->reserve_percent = 100 / ($request->price / $request->reserve_price);
        } else {
            $projectPlane->reserve_percent == 0;
        }
        $projectPlane->reserve_price = $request->reserve_price;
        if ($request->avance_price > 0) {
            $projectPlane->avance_percent = 100 / (($request->price - $request->reserve_price) / $request->avance_price);
        } else {
            $projectPlane->avance_percent == 0;
        }
        $projectPlane->status = $request->status;
        $projectPlane->pay = $projectPlane->price - $request->avance_price - $request->reserve_price;
        $planePay = ProjectPlan::where('project_id', '=', $projectPlane->project_id)
            ->where('status', '=', null)
            ->sum('pay');
        if ($projectPlane->status == 0) {
            $projectPlane->deadline_date = Carbon::now();
            $project = Project::find($projectPlane->project_id);
            $firstAvance = $project->avance;

            $reserve = Guarantee::where('project_id', $projectPlane->project_id)->where('type', 2)->get();
            $avance = Guarantee::where('project_id', $projectPlane->project_id)->where('type', 1)->get();
            $project->avance = $project->avance - $projectPlane->avance_price;
            $project->remaining_tax = $project->price - ($planePay + $project->start_avance);
            
            $project->reserve = $project->reserve + $request->reserve_price;
            // მუშაობს
            // $price1 = $projectPlane->reserve_price; 
            // foreach ($reserve as $guarantee) {
            //     if ($guarantee->reserve_price > 0) {
            //         if ($guarantee->reserve_price < $price1) {
            //             $price1 = $price1 - $guarantee->reserve_price;
            //             $guarantee->reserve_price = 0;
            //         }
            //         else if($guarantee->reserve_price > $price1){
            //             $guarantee->reserve_price = $guarantee->reserve_price - $price1;
            //             $price1 = 0;                        
            //         }
            //         $guarantee->update();
            //     }
            // }
            // foreach ($reserve as $guarantee) {
            //     if ($guarantee->price > 0) {
            //         if ($guarantee->price < $price1) {
            //             $price1 = $price1 - $guarantee->price;
            //             $guarantee->price = 0;
            //         }
            //         else if($guarantee->price > $price1){
            //             $guarantee->price = $guarantee->price - $price1;
            //             $price1 = 0;
            //         }
            //         $guarantee->update();
            //     }
            // }
            // -----------------
            $price = $projectPlane->avance_price;
            if($firstAvance <= $avance->sum('start_price') + $avance->sum('start_reserve_price')){
                foreach ($avance as $guarantee) {
                    if ($guarantee->reserve_price > 0) {
                        if ($guarantee->reserve_price < $price) {
                            $price = $price - $guarantee->reserve_price;
                            $guarantee->reserve_price = 0;
                        }
                        else{
                            $guarantee->reserve_price = $guarantee->reserve_price - $price;
                            $price = 0; 
                        }
                        $guarantee->update();
                    }
                }
                foreach ($avance as $guarantee) {
                    if ($guarantee->price > 0) {
                        if ($guarantee->price < $price) {
                            $price = $price - $guarantee->price;
                            $guarantee->price = 0;
                        }
                        else{
                            $guarantee->price = $guarantee->price - $price;
                            $price = 0;
                        }
                        $guarantee->update();
                    }
                }
            }
        }

        $project->update();
        $projectPlane->update();
        return redirect()->back();
    }


    public function updateGuaranteeStatus(Request $request, $id)
    {
        $projectPlane = ProjectPlan::find($id);
        $projectPlane->guarantee_status = $request->guarantee_status;
        $projectPlane->update();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $projectPlan = ProjectPlan::find($id);
        $projectPlan->delete();
        return redirect()->back();
    }
}
