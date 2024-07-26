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
        $uploadedPersonalFiles = [];
        if ($personalFiles) {
            foreach ($personalFiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();
                    $file->move($directoryPath, $filename);
                    $uploadedPersonalFiles[] = $filename;
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        // Handle certificate files upload
        $uploadedCertificateFiles = [];
        if ($certificateFiles) {
            foreach ($certificateFiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();
                    $file->move($directoryPath, $filename);
                    $uploadedCertificateFiles[] = $filename;
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        if (count($failedFiles) > 0) {
            // Xóa dữ liệu liên quan nếu có lỗi upload
            $id_contact = DB::table('employees')->where('id_employee', $id_employee)->value('id_contact');
            DB::table('employees')->where('id_employee', $id_employee)->delete();
            DB::table('contacts')->where('id_contact', $id_contact)->delete();
            DB::table('job_detail')->where('id_employee', $id_employee)->delete();
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        // Update database with uploaded file names
        DB::table('employees')
            ->where('id_employee', $id_employee)
            ->update([
                'photo' => $photo_filename,
                'cv' => json_encode($uploadedPersonalFiles),
            ]);
//        DB:table('contacts')->insert([])
//        'certificate_files' => json_encode($uploadedCertificateFiles)
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
            ->update(['photo' => 'uploads/' . $id_employee.'/'.$photo_filename]);

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

        // Check if $oldProfiles is null, set it to an empty array
        $oldProfiles = $oldProfiles ? json_decode($oldProfiles, true) : [];

        $uploadedFiles = [];
        $failedFiles = [];

        // Handle photo uploads
        if ($personalProfiles) {
            foreach ($personalProfiles as $file) {
                try {
                    $filename = $file->getClientOriginalName();

                    // Check if the new photo is the same as one of the old photos
                    if (!in_array($filename, (array)$oldProfiles)) { // Cast $oldProfiles to array to avoid null error
                        $file->move($directoryPath, $filename);
                        $uploadedFiles[] = $filename;
                    }
                } catch (\Exception $e) {
                    $failedFiles[] = $filename;
                }
            }
        }

        // Remove old photos that are not in the new uploads
        if (!empty($oldProfiles) && is_array($oldProfiles)) {
            foreach ($oldProfiles as $oldProfile) {
                if (!in_array($oldProfile, $uploadedFiles)) {
                    $oldProfilePath = $directoryPath . '/' . $oldProfile;
                    if (file_exists($oldProfilePath)) {
                        unlink($oldProfilePath);
                    }
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

    public function uploadMedicalCheckUp(Request $request)
    {
        $request->validate([
            'medical_file' => 'nullable|file|max:1048576', // 10MB
        ]);

        $medicalFile = $request->file('medical_file');
        $id_employee = $request->input('id_employee');
        $medical_checkup_date = $request->input('medical_checkup_date');
        $directoryPath = public_path('uploads/' . $id_employee);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $uploadedFile = null;
        $failedFile = null;

        // Handle file upload
        if ($medicalFile) {
            try {
                $filename = $medicalFile->getClientOriginalName();
                $medicalFile->move($directoryPath, $filename);
                $uploadedFile = $filename;
            } catch (\Exception $e) {
                $failedFile = $filename;
            }
        }

        DB::table('medical_checkup')->insert([
            'id_employee' => $id_employee,
            'medical_checkup_file' => $uploadedFile,
            'medical_checkup_issue_date' => $medical_checkup_date,
        ]);

        if ($failedFile) {
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }

    public function uploadCertificate(Request $request)
    {
        $request->validate([
            'certificate_file' => 'nullable|file|max:1048576', // 10MB
        ]);
        $certificate_file = $request->file('certificate_file');
        $id_employee = $request->input('id_employee');
        $certificate_end_date = $request->input('certificate_end_date');
        $type_certificate = $request->input('type_certificate');
        $directoryPath = public_path('uploads/' . $id_employee);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $uploadedFile = null;
        $failedFile = null;

        // Handle file upload
        if ($certificate_file) {
            try {
                $filename = $certificate_file->getClientOriginalName();
                $certificate_file->move($directoryPath, $filename);
                $uploadedFile = $filename;
            } catch (\Exception $e) {
                $failedFile = $filename;
            }
        }

        DB::table('certificates')->insert([
            'id_employee' => $id_employee,
            'certificate' => $uploadedFile,
            'id_type_certificate' => $type_certificate,
            'end_date_certificate' => $certificate_end_date
        ]);

        if ($failedFile) {
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }
}
