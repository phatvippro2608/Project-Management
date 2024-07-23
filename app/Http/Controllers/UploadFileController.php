<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadFileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|file|max:1048576', // 10MB
            'personal.*' => 'nullable|file|max:1048576', // 10MB per file
            'certificate.*' => 'nullable|file|max:1048576', // 10MB per file
        ]);

        $photo = $request->file('photo');
        $personalFiles = $request->file('personal');
        $certificateFiles = $request->file('certificate');
        $id_employee = $request->input('id_employee');

        $directoryPath = public_path('uploads/' . $id_employee);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $uploadedFiles = [];
        $failedFiles = [];

        // Handle photo upload
        $photo_filename = "";
        if ($photo) {
            try {
                $photo_filename = $photo->getClientOriginalName();
                $photo->move($directoryPath, $photo_filename);
                $uploadedFiles[] = $photo_filename;
            } catch (\Exception $e) {
                $failedFiles[] = $photo->getClientOriginalName();
            }
        }

        // Handle personal files upload
        if ($personalFiles) {
            foreach ($personalFiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();
                    $file->move($directoryPath, $filename);
                    $uploadedFiles[] = $filename;
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        // Handle certificate files upload
        if ($certificateFiles) {
            foreach ($certificateFiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();
                    $file->move($directoryPath, $filename);
                    $uploadedFiles[] = $filename;
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        if (count($failedFiles) > 0) {
            $id_contact = DB::table('employees')->where('id_employee', $id_employee)->value('id_contact');
            DB::table('employees')->where('id_employee', $id_employee)->delete();
            DB::table('contacts')->where('id_contact', $id_contact)->delete();
            DB::table('job_detail')->where('id_employee', $id_employee)->delete();
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }
        DB::table('employees')
            ->where('id_employee', $id_employee)
            ->update(['photo' => $photo_filename]);

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }

}
