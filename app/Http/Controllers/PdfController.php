<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectPlan;
use App\Models\Project;
use \PDF;

class PdfController extends Controller
{
    public function downloadPDF($id){
        $projectPlane = ProjectPlan::find($id);
        $project = Project::find($projectPlane->project_id);
        $data = [
            'title' => 'Project Plane ',
            'date' => date('d/m/Y'),
            'project' => $project,
            'plane' => $projectPlane,
            ];
            
            $pdf = PDF::loadView('pdf', $data);
            return $pdf->download('plane.pdf');

    }
}
