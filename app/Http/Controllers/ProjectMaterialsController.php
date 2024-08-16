<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectMaterialsController extends Controller
{
    public function getView(Request $request, $id){
        $indexLocation = $request->input('location', '');
        if(!empty($indexLocation)){
            $materialData = DB::table('project_materials')
            ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
            ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
            ->select('project_materials.*', 'project_locations.*', 'projects.project_name')
            ->where('projects.project_id', $id)->where('project_locations.project_location_id', $indexLocation)->get();
            
        }else{
            $materialData = DB::table('project_materials')
            ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
            ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
            ->select('project_materials.*', 'project_locations.*', 'projects.project_name')
            ->where('projects.project_id', $id)->get();
        }
        return view('auth.projects.project-materials',['materialData' => $materialData]);   
    }
}
