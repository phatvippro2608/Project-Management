<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ProjectLocationController extends Controller
{
    function addLocation(Request $request)
    {
        try {
            if(DB::table('project_locations')->insertGetId([
                'project_location_name' => $request->project_location_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'location_amount' => $request->location_amount,
                'project_id' => $request->project_id,
            ])){
                return AccountController::status('Added a project location', 200);
            }else{
                return AccountController::status('Fail to add project location', 500);
            }
        }catch(Exception $e){
            return AccountController::status('Fail to add project location', 500);
        }

    }
}
