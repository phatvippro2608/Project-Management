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

        $failedFiles = [];

        // Handle photo upload
        $photo_filename = "";
        if ($photo) {
            try {
                $photo_filename = $photo->getClientOriginalName();
                $photo->move($directoryPath, $photo_filename);
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

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|file|max:1048576', // 10MB
        ]);

        $photo = $request->file('photo');
        $id_employee = $request->input('id_employee');
        $directoryPath = public_path('uploads/' . $id_employee);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Get old photo filename
        $oldPhoto = DB::table('employees')->where('id_employee', $id_employee)->value('photo');
        $photo_filename = $photo ? $photo->getClientOriginalName() : '';

        // Check if the new photo is the same as the old photo
        if ($photo && $photo_filename === $oldPhoto) {
            return json_encode((object)["status" => 200, "message" => "Action Successful"]);
        }

        $uploadedFiles = [];
        $failedFiles = [];

        // Handle photo upload
        if ($photo) {
            try {
                $photo->move($directoryPath, $photo_filename);
                $uploadedFiles[] = $photo_filename;

                // Remove old photo from directory and update database
                if ($oldPhoto) {
                    $oldPhotoPath = $directoryPath . '/' . $oldPhoto;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }

                    // Update database to remove old photo reference
                    DB::table('employees')
                        ->where('id_employee', $id_employee)
                        ->update(['photo' => null]);
                }
            } catch (\Exception $e) {
                $failedFiles[] = $photo->getClientOriginalName();
            }
        }

        if (count($failedFiles) > 0) {
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        DB::table('employees')
            ->where('id_employee', $id_employee)
            ->update(['photo' => $photo_filename]);

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }


    public function uploadPersonalProfile(Request $request)
    {
        $request->validate([
            'personal_profile.*' => 'nullable|file|max:1048576', // 10MB
        ]);

        $personalProfiles = $request->file('personal_profile');
        $id_employee = $request->input('id_employee');
        $directoryPath = public_path('uploads/' . $id_employee);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Get old photo filenames
        $oldProfiles = DB::table('employees')->where('id_employee', $id_employee)->value('cv');
        $oldProfiles = $oldProfiles ? json_decode($oldProfiles, true) : [];

        $uploadedFiles = [];
        $failedFiles = [];

        // Handle photo uploads
        if ($personalProfiles) {
            foreach ($personalProfiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();

                    // Check if the new photo is the same as one of the old photos
                    if (!in_array($filename, $oldProfiles)) {
                        $file->move($directoryPath, $filename);
                        $uploadedFiles[] = $filename;
                    }
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        // Remove old photos that are not in the new uploads
        foreach ($oldProfiles as $oldProfile) {
            if (!in_array($oldProfile, $uploadedFiles)) {
                $oldProfilePath = $directoryPath . '/' . $oldProfile;
                if (file_exists($oldProfilePath)) {
                    unlink($oldProfilePath);
                }
            }
        }

        DB::table('employees')
            ->where('id_employee', $id_employee)
            ->update(['cv' => json_encode($uploadedFiles)]);

        if (count($failedFiles) > 0) {
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);

        }

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);

    }


}
