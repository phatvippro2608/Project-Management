<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\CustomerModel;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    function getView(){
        $contracts = ContractModel::all();
        $customers = CustomerModel::all();
        return view('auth.contracts.contract' ,['contracts'=>$contracts, 'customers'=>$customers]);
    }

    public function uploadContractFile(Request $request)
    {
        $uploadedFiles = [];
        if ($request->hasFile('contractAttachment')) {
            $files = $request->file('contractAttachment');

            foreach ($files as $file) {
                $file_name = $file->getClientOriginalName();
                $folder = uniqid('contractAttachment-', true);
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

        if (!empty($uploadedFiles)) {
            return json_encode([
                'uploaded_files' => $uploadedFiles,
            ]);
        }
        return '';
    }

    public function addContract(Request $request)
    {
        $rules = [
            'contract_name' => 'required|string|max:255|min:5',
            'contract_date' => 'required|date|before:contract_end_date',
            'contract_end_date' => 'required|date',
            'contract_details' => 'required|string',
            'customer_id' => 'required|int',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return AccountController::status(json_encode($errors->toArray()), 422);
        }


        $contract_id = DB::table('contracts')->insertGetId([
            'contract_name' => $request->contract_name,
            'contract_date' => $request->contract_date,
            'contract_end_date' => $request->contract_end_date,
            'contract_details' => $request->contract_details,
            'customer_id' => $request->customer_id,
        ]);
        if($contract_id) {
            $fileData = [];
            if($request->contractAttachment){
                $fileData = $request->contractAttachment;
            }
            foreach ($fileData as $fileDataJson) {
                $fileData = json_decode($fileDataJson, true);
                $folders = $fileData['uploaded_files'];

                foreach ($folders as $folderData) {
                    $tmp_file = TemporaryFile::where('folder', $folderData['folder'])->first();

                    if ($tmp_file) {
                        $path = 'contracts/' . $contract_id . '/' .time(). $tmp_file->file;
                        $fileContentPath = 'uploads/' . $tmp_file->folder . '/' . $tmp_file->file;
                        $fileContent = Storage::disk('public')->get($fileContentPath);
                        Storage::disk('public_uploads')->put($path, $fileContent);
                        DB::table('contract_files')->insert([
                            'file_name' => $tmp_file->file,
                            'file_path' => "/".$path,
                            'contract_id' => $contract_id,
                        ]);
                        Storage::disk('public')->deleteDirectory('uploads/' . $tmp_file->folder);
                        $tmp_file->delete();
                    }
                }
            }

            return AccountController::status('Added a new contract', 200);
        }
        return AccountController::status('Failed to add contract', 500);
    }

    public function updateContract(Request $request)
    {
        $rules = [
            'contract_name' => 'required|string|max:255|min:5',
            'contract_date' => 'required|date|before:contract_end_date',
            'contract_end_date' => 'required|date',
            'contract_details' => 'required|string',
            'customer_id' => 'required|int',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return AccountController::status(json_encode($errors->toArray()), 422);
        }

        DB::table('contracts')->where('contract_id', $request->contract_id)->update([
            'contract_name' => $request->contract_name,
            'contract_date' => $request->contract_date,
            'contract_end_date' => $request->contract_end_date,
            'contract_details' => $request->contract_details,
            'customer_id' => $request->customer_id,
        ]);
        if($request->contract_id) {
            $fileData = [];
            if($request->contractAttachment){
                $fileData = $request->contractAttachment;
            }
            foreach ($fileData as $fileDataJson) {
                $fileData = json_decode($fileDataJson, true);
                $folders = $fileData['uploaded_files'];

                foreach ($folders as $folderData) {
                    $tmp_file = TemporaryFile::where('folder', $folderData['folder'])->first();

                    if ($tmp_file) {
                        $path = 'contracts/' . $request->contract_id . '/'. time() . $tmp_file->file;
                        $fileContentPath = 'uploads/' . $tmp_file->folder . '/' . $tmp_file->file;
                        $fileContent = Storage::disk('public')->get($fileContentPath);
                        Storage::disk('public_uploads')->put($path, $fileContent);
                        DB::table('contract_files')->insert([
                            'file_name' => $tmp_file->file,
                            'file_path' => "/".$path,
                            'contract_id' => $request->contract_id,
                        ]);
                        Storage::disk('public')->deleteDirectory('uploads/' . $tmp_file->folder);
                        $tmp_file->delete();
                    }
                }
            }

            return AccountController::status('Updated a contract', 200);
        }
        return AccountController::status('Failed to update contract', 500);
    }

    function deleteContract(Request $request)
    {
        try {
            $contract_id = $request->contract_id;

            $fileList = DB::table('contract_files')->where('contract_id', $contract_id)->pluck('file_path');

            foreach ($fileList as $file_path) {
                $path = public_path($file_path);

                if (file_exists($path)) {
                    unlink($path);
                }

            }
            DB::table('contract_files')->where('contract_id', $contract_id)->delete();
            DB::table('contracts')->where('contract_id', $contract_id)->delete();

            return AccountController::status('Deleted contract and associated files', 200);
        }catch (\Exception $exception){
            return AccountController::status('Fail to delete contract and associated files', 500);
        }
    }


    function deleteFileContract(Request $request)
    {
        $contract_file_id = $request->contract_file_id;
        $file_path = DB::table('contract_files')->where('contract_file_id', $contract_file_id)->first()->file_path;
        $path = public_path($file_path);

        if (file_exists($path)) {
            unlink($path);
            DB::table('contract_files')->where('contract_file_id', $contract_file_id)->delete();
            return AccountController::status('Deleted contract file', 200);
        } else {
            echo "File does not exist.";
            DB::table('contract_files')->where('contract_file_id', $contract_file_id)->delete();
            return AccountController::status('Fail to delete contract file', 500);
        }

    }

    static function getFile($contract_id)
    {
        return DB::table('contract_files')->where('contract_id', $contract_id)->get();
    }
}
