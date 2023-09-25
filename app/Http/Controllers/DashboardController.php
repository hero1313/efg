<?php

namespace App\Http\Controllers;

use App\Models\Guarantee;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectPlan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $projects = Project::where('status',1)->get();
        // ჩარიცხული ავანსი - საწყისი ავანსების ჯამი ყველა პროექტში
        $startAvanceSum = Project::where('status','=',1)->sum('start_avance');
        // პროექტების რაოდენობა - სულ რამდენი პროექტია
        $projectsQuantity = Project::all()->count();
        // აქტიური პროექტები - სულ აქტიური პროექტების ოდენობა
        $activeProjectsQuantity = Project::where('status',1)->count();
        // დასრულებული პროექტები - სულ დასრულებული პროექტების ოდენობა
        $endedProjectsQuantity = Project::where('status','!=',1)->count();
        // დასრულებული პროექტების თანხების ჯამი 
        $endedProjectsPrice = Project::where('status','!=',1)->sum('price');
        // აქტიური პროექტების თანხების ჯამი
        $activeProjectsPrice = Project::where('status','=',1)->sum('price');
        // სახელშეკრულებო ღირებულება - ყველა არსებული პროექტების  ჯამური ღირებულება
        $projectsCost = Project::where('status','=',1)->sum('price');
        // შესრულებული სამუშაო - ყველა გეგმის ღირებულების ჯამი რომელიც დასრულებულია
        $doneWork =  ProjectPlan::where('status', null)->where('project_status', 1)->sum('price');
        // აქტიური გეგმების ღირებულება 
        $activePlane =  ProjectPlan::where('status', 1)->where('project_status', 1)->sum('price');
        // გადახდილი თანხა - ყველა გეგმის გადასახდელი თანხების ჯამი ანუ (გეგმის ღირებულებას გამოკლებული რეზერვი და ავანსი)
        $amountPaid =  ProjectPlan::where('status', null)->where('project_status', 1)->sum('pay');
        // დარჩენილი სამუშაო - ყველა არსებული და დასრულებული პროექტების  ჯამური ღირებულებას გამოკლებული ყველა გეგმის გადასახდელი თანხების ჯამი
        $remainingWork =  $projectsCost - $doneWork; 
        // დარჩენილი გადასახადი - ყველა არსებული და დასრულებული პროექტების  ჯამური ღირებულებას გამოკლებული ყველა გეგმის ღირებულების ჯამი რომელიც დასრულებულია 
        // ეს ითვლის პროექტიდან
        // გაუქვითავი ავანსი - დარჩენილი ავანსების ჯამი 
        $remainingAvance = Project::where('status','=',1)->sum('avance') ;
        // გაქვითული ავანსი - საწყისი ავანსების ჯამს გამოკლებული დარჩენილი ავანსები
        $paidAvance = $startAvanceSum - $remainingAvance;
        // სარეზერვო თანხა - აქტიურ პროექტებში სარეზერვო თანხები
        $reserveSum = Project::where('status',1)->sum('start_reserve');
        // დაკავებული სარეზერვო თანხა - აქტიურ პროექტებში დაკავებული სარეზერვო თანხები
        $busyReserve = Project::where('status',1)->sum('reserve');
        // დაუკავებული სარეზერვო თანხა - აქტიურ პროექტებში დაუკავებული სარეზერვო თანხები
        $unrestrainedReservePrice = $reserveSum - $busyReserve;
        // რეზერვის გარანტიის ჯამი - ყველა სარეზერვო გარანტიის საწყისი ღირებულების ჯამი 
        $reserveStartGuarentee = Guarantee::where('deadline','>', $today)->where('type', 2)->sum('start_price') + Guarantee::where('type', 2)->sum('start_reserve_price');
        $cashGuarentee = Guarantee::where('deadline','>', $today)->sum('price');
        // ავანსის გარანტიის ჯამი -  ყველა საავანსე გარანტიის საწყისი ღირებულების ჯამი
        $avanceStartGuarentee = Guarantee::where('deadline','>', $today)->where('type', 1)->sum('start_price') + Guarantee::where('type', 1)->sum('start_reserve_price');
        $udzraviGuarentee = Guarantee::where('deadline','>', $today)->sum('reserve_price');
        // მიმდინარე გარანტიების ოდენობა - ჯამურად ყველა დაუკავებელი გარანტიის ოდენობა
        $activeGuarenteePrice = $cashGuarentee + $udzraviGuarentee;
        // ქეშით უზრუნველყოფილი გარანტიები - ყველა აქტიური ქეშით უზრუნველყოფილი გარანტია
        $grantsCash = Guarantee::where('deadline','>', $today)->sum('price');

        // ქონებით უზრუნველყოფილი გარანტიები - ყველა აქტიური ქონებით უზრუნველყოფილი გარანტია
        $grantUdzravi = Guarantee::where('deadline','>', $today)->sum('reserve_price');

        // გარანტიების ლიმიტი - ჯამურად ყველა გარანტიის საწყისი ღირებულება
        $grantLimit = $grantsCash + $grantUdzravi;

        // გამონთავისუფლებული თანხა - გარანტიის ლიმიტს გამოკლებული დაკავებული გრანტების ოდენობა;
        $freeGuarantees = $grantLimit - ($udzraviGuarentee + $cashGuarentee);
        // dd($udzraviGuarentee + $cashGuarentee);
        // dd($reserveGuarentee + $avanceGuarentee);

        // გამონთავისუფლებული ჩარიცხული
        $freecharicxuli = ProjectPlan::where('status', '=', null)->where('guarantee_status', '=', 1)->sum('avance_price');

        // ბოლო გეგმები
        $lastPlanes = ProjectPlan::where('status', '=', null)->latest()->take(15)->get();


        return view('components.dashboard', compact(['freecharicxuli','activePlane', 'projects','reserveSum','busyReserve','projectsQuantity','activeProjectsQuantity','endedProjectsQuantity','endedProjectsPrice','activeProjectsPrice','projectsCost','doneWork','amountPaid', 'remainingWork','startAvanceSum','remainingAvance','paidAvance','unrestrainedReservePrice','reserveStartGuarentee','avanceStartGuarentee','activeGuarenteePrice','grantsCash','grantUdzravi','grantLimit','freeGuarantees','lastPlanes']));

    }
}