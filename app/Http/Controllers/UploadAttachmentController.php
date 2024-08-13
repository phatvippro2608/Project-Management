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
        if($request->has('fileAttachment') || $request->has('imageAttachment')) {
            $fileData = [];
            if($request->fileAttachment){
                $fileData = $request->fileAttachment;
            }
            if($request->imageAttachment){
                $fileData = $request->imageAttachment;
            }
            $firstFileData = json_decode($fileData[0], true);
            $project_location_id = $firstFileData['project_location_id'];
            $date = $firstFileData['date'];
            $type = $firstFileData['type'];

            // Iterate over each file's data
            foreach ($fileData as $fileDataJson) {
                $fileData = json_decode($fileDataJson, true);

                // Get the uploaded files' folder data
                $folders = $fileData['uploaded_files'];

                foreach ($folders as $folderData) {
                    // Retrieve the temporary file from the database
                    $tmp_file = TemporaryFile::where('folder', $folderData['folder'])->first();

                    if ($tmp_file) {
                        // Define the new path for the file
                        $path = 'attachments/' . $project_location_id . '/' . $tmp_file->file;
                        $fileContentPath = 'uploads/' . $tmp_file->folder . '/' . $tmp_file->file;

                        // Get the file content from the temporary location
                        $fileContent = Storage::disk('public')->get($fileContentPath);

                        // Store the file in the new location
                        Storage::disk('public_uploads')->put($path, $fileContent);

                        // Insert the file information into the appropriate database table
                        if ($type == 'file') {
                            DB::table('task_files')->insert([
                                'file_name' => $tmp_file->file,
                                'project_location_id' => $project_location_id,
                                'date' => $date
                            ]);
                        } else if ($type == 'image') {
                            DB::table('task_images')->insert([
                                'image_name' => $tmp_file->file,
                                'project_location_id' => $project_location_id,
                                'date' => $date
                            ]);
                        }

                        // Delete the temporary folder
                        Storage::disk('public')->deleteDirectory('uploads/' . $tmp_file->folder);

                        // Delete the temporary file record from the database
                        $tmp_file->delete();
                    }
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

        // Initialize an array to store uploaded files information
        $uploadedFiles = [];

        // Process file attachments
        if ($request->hasFile('fileAttachment')) {
            $files = $request->file('fileAttachment');

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
        }

        // Process image attachments
        if ($request->hasFile('imageAttachment')) {
            $images = $request->file('imageAttachment');

            foreach ($images as $image) {
                $image_name = $image->getClientOriginalName();
                $folder = uniqid('imageAttachment-', true);
                $image->storeAs('uploads/' . $folder, $image_name);

                // Save image information in the database
                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $image_name,
                ]);

                // Store uploaded image information for the response
                $uploadedFiles[] = [
                    'folder' => $folder,
                    'file' => $image_name,
                ];
            }
        }

        // Return the response with all uploaded files
        if (!empty($uploadedFiles)) {
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
