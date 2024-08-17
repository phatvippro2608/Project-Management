<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PRMController extends Controller
{
    static public function getView()
    {
        $project = DB::table('projects')
            ->join('contracts', 'contracts.contract_id', '=', 'projects.contract_id')
            ->join('customers', 'customers.customer_id', '=', 'contracts.customer_id')
            ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
            ->join('project_teams', 'project_teams.project_id', '=', 'projects.project_id')
            ->select(
                'project_teams.*',
                'projects.*',
                DB::raw("CONCAT(customers.company_name, ' - ', customers.last_name, ' ', customers.first_name) AS customer_info"),
                'phases.phase_name_eng'
            )
            ->orderBy('project_teams.project_id', 'asc')
            ->get();
        foreach ($project as $item) {
            $item->team_members = DB::table('team_details')
                ->join('employees', 'employees.employee_id', '=', 'team_details.employee_id')
                ->where('team_id', $item->team_id)
                ->orderBy('team_permission', 'asc')
                ->get();


            $sql = "SELECT
                    employees.*, accounts.*,team_details.team_permission as team_permission,
                    CASE
                        WHEN team_details.employee_id IS NOT NULL THEN 1
                        ELSE 0
                    END AS isAtTeam
                FROM
                    employees
                JOIN
                    accounts ON accounts.employee_id = employees.employee_id
                LEFT JOIN
                    team_details ON team_details.employee_id = employees.employee_id AND team_details.team_id = $item->team_id
                ";

            $item->all_employees = DB::select($sql);

            $item->locations = DB::table('project_locations')->where('project_id', $item->project_id)->get();
        }
        $teams = DB::table('teams')->get();
        $team_positions = DB::table("team_positions")->get();
        $contracts = DB::table('contracts')
            ->leftjoin('projects', 'contracts.contract_id', '=', 'projects.contract_id')
            ->whereNull('projects.contract_id')->select('contracts.contract_id', 'contracts.contract_name')->get();
        return view('auth.prm.project.project', compact('project', 'teams', 'contracts', 'team_positions'));
    }
}
