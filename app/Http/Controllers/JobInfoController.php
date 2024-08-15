<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobInfoController extends Controller
{
    public function getView()
    {
        return view('auth.employees.job');
    }

    public function getJob(Request $request)
    {
        $table = $request->job;
        $columns = $this->getColumnsForTable($table);

        if ($columns) {
            $job = DB::table($table)->where('status','show')->select($columns)->get();
            return response()->json(['status' => 200, 'data' => $job]);
        } else {
            return response()->json(['status' => 400, 'message' => 'Invalid table name']);
        }

    }
    private function getColumnsForTable($table)
    {
        $columns = [
            'job_type_contracts' => ['type_contract_id', 'type_contract_name'],
            'job_locations' => ['location_id', 'location_name'],
            'job_titles' => ['job_title_id', 'job_title'],
            'job_teams' => ['team_id', 'team_name'],
            'job_positions' => ['position_id', 'position_name'],
            'job_categories' => ['job_category_id', 'job_category_name'],
            'job_levels' => ['level_id', 'level_name'],
            'job_countries' => ['country_id', 'country_name']
        ];

        return $columns[$table] ?? null;
    }
    public function add(Request $request)
    {
        $job_table = $request->job;
        $job_name = $request->job_name;
        $column_name = $this->getColumnsForTable($job_table)[1];
        if(DB::table($job_table)->where($column_name, $job_name)->where('status', 'show')->exists()){
            return response()->json(['status' => 400, 'message' => 'Name is exist!']);
        }
        $add = DB::table($job_table)->insert([$column_name => $job_name, 'status' => 'show']);
        if ($add) {
            return response()->json(['status' => 200,'Action Successful']);
        }else{
            return response()->json(['status' => 400,'Action Failed']);
        }
    }

    public function update(Request $request)
    {
        $job_table = $request->job;
        $id_job_name = $request->id_job_name;
        $job_name = $request->job_name;
        $column_id = $this->getColumnsForTable($job_table)[0];
        $column_name = $this->getColumnsForTable($job_table)[1];
        $old_name = DB::table($job_table)->where($column_id, $id_job_name)->value($column_name);
        if($old_name ==  $job_name){
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        }

        $updated = DB::table($job_table)->where($column_id,$id_job_name)->update([$column_name => $job_name]);
        if ($updated) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Action Failed']);
        }
    }

    public function delete(Request $request)
    {
        $job_table = $request->job;
        $job_id = $request->job_id;
        $column_id = $this->getColumnsForTable($job_table)[0];
        $sql = DB::table($job_table)->where($column_id,$job_id)->update(['status' => 'hide']);
        if ($sql) {
            return response()->json(['status' => 200, 'message' => 'Action Success']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Action Failed']);
        }
    }
}
