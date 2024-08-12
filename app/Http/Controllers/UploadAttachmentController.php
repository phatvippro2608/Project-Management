<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadAttachmentController extends Controller
{
    public function attachmentStore(Request $request)
    {
        $fileData = $request->input('fileAttachment');
        $firstFileData = json_decode($fileData[0], true);
        $project_location_id = $firstFileData['project_location_id'];
        $date = $firstFileData['date'];
        $type = $firstFileData['type'];
        foreach ($fileData as $fileDataJson) {
            $fileData = json_decode($fileDataJson, true);

            $folders = $fileData['uploaded_files'];

            foreach ($folders as $folderData) {
                $tmp_file = TemporaryFile::where('folder', $folderData['folder'])->first();

                if ($tmp_file) {
                    $path = 'attachments/' . $project_location_id . '/' . $tmp_file->file;
                    $fileContentPath = 'uploads/' . $tmp_file->folder . '/' . $tmp_file->file;
                    $fileContent = Storage::disk('public')->get($fileContentPath);

                    Storage::disk('public_uploads')->put($path, $fileContent);

                    if($type == 'file'){
                        DB::table('task_files')->insert(
                            ['file_name' => $tmp_file->file, 'project_location_id' => $project_location_id,'date' => $date]
                        );
                    }
                    else if ($type == 'image'){
                        DB::table('task_images')->insert(
                            ['image_name' => $tmp_file->file, 'project_location_id' => $project_location_id,'date' => $date]
                        );
                    }

                    Storage::disk('public')->deleteDirectory('uploads/' . $tmp_file->folder);

                    $tmp_file->delete();
                }
            }
        }
        return redirect()->back();

    }
    public function attachmentUpload(Request $request)
    {
        $project_location_id = $request->project_location_id;
        $date = $request->date;
        $type = $request->type;
        if ($request->hasFile('fileAttachment')) {
            $files = $request->file('fileAttachment');
            $uploadedFiles = [];

            foreach ($files as $file) {
                $file_name = $file->getClientOriginalName();
                $folder = uniqid('fileAttachment-', true);
                $file->storeAs('uploads/' . $folder, $file_name);

                // Save file information in the database
                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $file_name,
                ]);

                // Store uploaded file information for the response
                $uploadedFiles[] = [
                    'folder' => $folder,
                    'file' => $file_name,
                ];
            }
//            dd($uploadedFiles);
            return json_encode([
                'uploaded_files' => $uploadedFiles,
                'project_location_id' => $project_location_id,
                'date' => $date,
                'type' => $type,
            ]);
        }
        return '';
    }

    public function attachmentDelete(Request $request)
    {
        $tmp_image = TemporaryFile::where('folder', $request->getContent())->first();
        if($tmp_image){
            Storage::disk('public')->deleteDirectory('uploads/attachments' . $tmp_image->folder);
            TemporaryFile::where('folder', $request->getContent())->delete();
        }
        return '';
    }
}
