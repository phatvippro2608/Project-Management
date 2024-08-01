<?php
namespace App\Http\Controllers;

use App\Services\MyXteamService;
use Illuminate\Http\Request;
use App\Mail\ProjectUpdateNotification;
use Illuminate\Support\Facades\Mail;

class MyXteamController extends Controller
{
    protected $myXteamService;

    public function __construct(MyXteamService $myXteamService)
    {
        $this->myXteamService = $myXteamService;
    }

    public function getProjects()
    {
        $response = $this->myXteamService->makeRequest('GET', '/projects');
        return response()->json($response);
    }

    public function createProject(Request $request)
    {
        $data = $request->all();
        $response = $this->myXteamService->makeRequest('POST', '/projects', $data);
        return response()->json($response);
    }

    public function getView()
    {
        $response = $this->myXteamService->makeRequest('GET', 'api/v1/Team/getAll');

        // Kiểm tra nếu response có lỗi
        if (isset($response['ErrorCode']) && $response['ErrorCode'] != 0) {
            return response()->json(['error' => $response['ErrorMessage']], 500);
        }

        return view('auth.myxteam.team', ['teams' => $response['Data']]);
    }

    public function getTeamProjects($WorkspaceId)
    {
        // Logic để lấy thông tin chi tiết và dự án của team
        $response = $this->myXteamService->makeRequest('GET', "api/v1/Project/getListByTeam?WorkspaceId=".$WorkspaceId);

        if (isset($response['ErrorCode']) && $response['ErrorCode'] != 0) {
            return response()->json(['error' => $response['ErrorMessage']], 500);
        }

        return view('auth.myxteam.team_projects', ['projects' => $response['Data'], 'WorkspaceId' => $WorkspaceId]);
    }

    public function getProjectTasks($WorkspaceId, $ProjectId)
    {
        // Logic để lấy thông tin chi tiết và dự án của team
        $response = $this->myXteamService->makeRequest('GET', "api/v1/Task/getTaskByProject?ProjectId=".$ProjectId);

        if (isset($response['ErrorCode']) && $response['ErrorCode'] != 0) {
            return response()->json(['error' => $response['ErrorMessage']], 500);
        }

        return view('auth.myxteam.project_tasks', ['tasks' => $response['Data'], 'ProjectId' => $ProjectId, 'WorkspaceId' => $WorkspaceId]);
    }

    public function updateTask(Request $request, $WorkspaceId, $ProjectId, $TaskId)
    {
        $data = $request->all();
        $data['TaskId'] = $TaskId; // Ensure TaskId is included in the data

        $response = $this->myXteamService->makeRequest('POST', 'api/v1/Task/update', $data);

        if (isset($response['ErrorCode']) && $response['ErrorCode'] != 0) {
            return response()->json([
                'status' => 'error',
                'message' => $response['ErrorMessage'],
                'error' => $response
            ], 500);
        }

        $taskDetails = $response['Data'];

        $bccEmails = [];
        foreach ($taskDetails['Followers'] as $user) {
            $bccEmails[] = $user['Email'];
        }

        $details = [
            'message' => '<b>Công việc</b> ' . $taskDetails['TaskName'] . ' đã được cập nhật. <b>Chi tiết: </b>' . $taskDetails['TaskName']
        ];

        Mail::to('huutho.cse@gmail.com')->bcc($bccEmails)->send(new ProjectUpdateNotification($details));

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully!'
        ]);
    }
}
