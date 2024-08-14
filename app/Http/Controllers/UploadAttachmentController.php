<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Polyfill\Intl\Normalizer\Normalizer;

class UploadAttachmentController extends Controller
{
    static function removeVietnameseAccents($str)
    {
        $str = mb_convert_encoding($str, 'UTF-8', 'auto');

        $str = normalizer_normalize($str, Normalizer::FORM_D);

        $str = preg_replace('/\p{M}/u', '', $str);

        return $str;
    }
    public function attachmentStore(Request $request)
    {
        if ($request->has('fileAttachment') || $request->has('imageAttachment')) {
            $fileData = [];
            if ($request->fileAttachment) {
                $fileData = $request->fileAttachment;
            }
            if ($request->imageAttachment) {
                $fileData = $request->imageAttachment;
            }
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
                        $path = 'attachments/' . $project_location_id . '/' . self::removeVietnameseAccents($tmp_file->file);
                        $fileContentPath = 'uploads/' . $tmp_file->folder . '/' . $tmp_file->file;
                        $fileContent = Storage::disk('public')->get($fileContentPath);

                        Storage::disk('public_uploads')->put($path, $fileContent);

                        if ($type == 'file') {
                            DB::table('task_files')->insert([
                                'file_name' => self::removeVietnameseAccents($tmp_file->file),
                                'project_location_id' => $project_location_id,
                                'date' => $date
                            ]);
                        } else if ($type == 'image') {
                            DB::table('task_images')->insert([
                                'image_name' => self::removeVietnameseAccents($tmp_file->file),
                                'project_location_id' => $project_location_id,
                                'date' => $date
                            ]);
                        }

                        Storage::disk('public')->deleteDirectory('uploads/' . $tmp_file->folder);

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

        $uploadedFiles = [];

        if ($request->hasFile('fileAttachment')) {
            $files = $request->file('fileAttachment');

            foreach ($files as $file) {
                $file_name = self::removeVietnameseAccents($file->getClientOriginalName());
                $folder = uniqid('fileAttachment-', true);
                $file->storeAs('uploads/' . $folder, $file_name);
                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $file_name,
                ]);

                $uploadedFiles[] = [
                    'folder' => $folder,
                    'file' => $file_name,
                ];
            }
        }

        if ($request->hasFile('imageAttachment')) {
            $images = $request->file('imageAttachment');

            foreach ($images as $image) {
                $image_name = self::removeVietnameseAccents($image->getClientOriginalName());
                $folder = uniqid('imageAttachment-', true);
                $image->storeAs('uploads/' . $folder, $image_name);

                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $image_name,
                ]);

                $uploadedFiles[] = [
                    'folder' => $folder,
                    'file' => $image_name,
                ];
            }
        }

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
