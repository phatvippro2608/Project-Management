<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function imageUpload(Request $request)
    {
        if ($request->hasFile('filepond')) {
            $file = $request->file('filepond');
            $file_name = $file->getClientOriginalName();
            $folder = uniqid('img-', true);
            $file->storeAs('images/tmp/' . $folder, $file_name);
            TemporaryFile::create([
                'folder' => $folder,
                'file' => $file
            ]);
            return $folder;
        }
        return '';
    }

    public function imageDelete(Request $request){
        $temporaryImage = TemporaryFile::where('folder', $request->getContent())->first();
        if($temporaryImage){
            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
            TemporaryFile::where('folder', $request->getContent())->delete();
        }
        return response()->noContent();
    }

    public function imageStore(Request $request)
    {
//        $temporaryImages = TemporaryFile::all();
////        foreach($temporaryImages as $temporaryImage){
////            Storage::deleteDirectory('images/tmp/' . $temporaryImage->folder);
////            TemporaryFile::where('folder', $request->getContent())->delete();
////        }
//        foreach ($temporaryImages as $temporaryImage) {
//            Storage::copy('images/tmp/' . $temporaryImage->folder . '/' . $temporaryImage->file, 'assets/images/'.$temporaryImage->folder.'/'.$temporaryImage->file);
//            Images::create([
//                'name' => $temporaryImage->file,
//                'path' => $temporaryImage->folder.'/'.$temporaryImage->file,
//            ]);
//            Storage::deleteDirectory('images/tmp/'.$temporaryImage->folder);
//            TemporaryFile::where('folder', $request->getContent())->delete();
//        }
//        return redirect()->route('view_image_upload');
        dd('hello');
    }
}
