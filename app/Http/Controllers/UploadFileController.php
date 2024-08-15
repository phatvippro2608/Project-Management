<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\PostFile;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $employee_id = $request->input('employee_id');

        $directoryPath = public_path('uploads/' . $employee_id);

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
            $contact_id = DB::table('employees')->where('employee_id', $employee_id)->value('contact_id');
            DB::table('employees')->where('employee_id', $employee_id)->delete();
            DB::table('contacts')->where('contact_id', $contact_id)->delete();
            DB::table('job_detail')->where('employee_id', $employee_id)->delete();
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        // Update database with uploaded file names
        DB::table('employees')
            ->where('employee_id', $employee_id)
            ->update([
                'photo' => 'uploads/'.$employee_id . '/' . $photo_filename,
                'cv' => json_encode($uploadedPersonalFiles),
            ]);
//        DB:table('contacts')->insert([])
//        'certificate_files' => json_encode($uploadedCertificateFiles)
        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:1048576', // 1MB
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation failure
            return response()->json([
                'status' => 422,
                'message' => 'File is not Image File please choose again!',
            ], 422);
        }
        $photo = $request->file('photo');
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $directoryPath = public_path('uploads/' . $employee_id);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Get old photo filename
        $oldPhoto = DB::table('employees')->where('employee_id', $employee_id)->value('photo');
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
                        ->where('employee_id', $employee_id)
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
            ->where('employee_id', $employee_id)
            ->update(['photo' => 'uploads/' . $employee_id.'/'.$photo_filename]);

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }


    public function uploadPersonalProfile(Request $request)
    {
        $request->validate([
            'personal_profile.*' => 'nullable|file|max:1048576', // 1MB
        ]);

        $personalProfiles = $request->file('personal_profile');
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $directoryPath = public_path('uploads/' . $employee_id);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        // Get old profile filenames
        $oldProfiles = DB::table('employees')->where('employee_id', $employee_id)->value('cv');
        $oldProfiles = $oldProfiles ? json_decode($oldProfiles, true) : [];

        $uploadedFiles = [];
        $failedFiles = [];

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

        // Combine old and new file lists, ensuring no duplicates
        $allProfiles = array_unique(array_merge($oldProfiles, $uploadedFiles));

        // Remove old files that are not in the new uploads
        foreach ($oldProfiles as $oldProfile) {
            if (!in_array($oldProfile, $allProfiles)) {
                $oldProfilePath = $directoryPath . '/' . $oldProfile;
                if (file_exists($oldProfilePath)) {
                    unlink($oldProfilePath);
                }
            }
        }

        // Update the employee's cv in the database
        DB::table('employees')
            ->where('employee_id', $employee_id)
            ->update(['cv' => json_encode($allProfiles)]);


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
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $medical_checkup_date = $request->input('medical_checkup_date');
        $directoryPath = public_path('uploads/' . $employee_id);

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
            'employee_id' => $employee_id,
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
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $certificate_end_date = $request->input('certificate_end_date');
        $type_certificate = $request->input('type_certificate');
        $directoryPath = public_path('uploads/' . $employee_id);

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
            'employee_id' => $employee_id,
            'certificate' => $uploadedFile,
            'type_certificate_id' => $type_certificate,
            'end_date_certificate' => $certificate_end_date
        ]);

        if ($failedFile) {
            return json_encode((object)["status" => 500, "message" => "Action Failed"]);
        }

        return json_encode((object)["status" => 200, "message" => "Action Successful"]);
    }

    public function imgStore(Request $request)
    {
        $imageData = json_decode($request->input('image'), true);
        $employee_id = $imageData['employee_id'];
        $folder = $imageData['folder'];
        $tmp_file = TemporaryFile::where('folder', $folder)->first();
        if($tmp_file){
            $path = 'uploads/'.$employee_id.'/'.$tmp_file->file;
            $fileContentPath = 'uploads/img/'. $tmp_file->folder .'/'. $tmp_file->file;
            $fileContent = Storage::disk('public')->get($fileContentPath);
            Storage::disk('public_uploads')->put($path, $fileContent);
            DB::table('employees')->where('employee_id', $employee_id)->update(['photo' => $path]);
            Storage::disk('public')->deleteDirectory('uploads/img/' . $tmp_file->folder);
            return redirect()->back();
        }
        return '';
    }
    public function imgUpload(Request $request)
    {
        if($request->employee_code){
            $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        }else{
            $employee_id = $request->employee_id;
        }
        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = $image->getClientOriginalName();
            $folder = uniqid('image-', true);
            $image->storeAs('uploads/img/'.$folder, $file_name);
            TemporaryFile::create([
                'folder'=>$folder,
                'file'=>$file_name,
            ]);
            return response()->json([
                'folder' => $folder,
                'employee_id' => $employee_id
            ]);
        }
        return '';
    }

    public function imgDelete(Request $request)
    {
        $tmp_image = TemporaryFile::where('folder', $request->getContent())->first();
        if($tmp_image){
            Storage::disk('public')->deleteDirectory('uploads/img/' . $tmp_image->folder);
            TemporaryFile::where('folder', $request->getContent())->delete();
        }
        return '';
    }

    public function uploadEmploymentContract(Request $request)
    {
        $request->validate([
            'employment_contract_file' => 'nullable|file|max:1048576', // 10MB
        ]);

        $employment_contract = $request->file('employment_contract_file');
        $employee_id = DB::table('employees')->where('employee_code', $request->employee_code)->value('employee_id');
        $employment_contract_start_date = $request->input('start_date');
        $employment_contract_end_date = $request->input('end_date');
        $directoryPath = public_path('uploads/' . $employee_id);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        $uploadedFile = null;
        $failedFile = null;

        // Handle file upload
        if ($employment_contract) {
            try {
                $filename = $employment_contract->getClientOriginalName();
                $employment_contract->move($directoryPath, $filename);
                $uploadedFile = $filename;
            } catch (\Exception $e) {
                $failedFile = $filename;
            }
        }

        DB::table('employment_contract')->insert([
            'employee_id' => $employee_id,
            'employment_contract' => $uploadedFile,
            'start_date' => $employment_contract_start_date,
            'end_date' => $employment_contract_end_date
        ]);

        if ($failedFile) {
            return response()->json(['status' => 400,'Action Failed']);
        }

        return response()->json(['status' => 200,'Action Successful']);
    }
}
