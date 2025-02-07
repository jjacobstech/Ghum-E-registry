<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Files;
use App\Rules\Checkuser;
use App\Helpers\FileHelper;
use Cloudinary\Api\Admin\AdminApi;
use Illuminate\Http\Request;
use App\Models\ArchivedFiles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class FilesController extends Controller
{
    public function shareFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'receiver' => ['required', 'string', new Checkuser],
            'file' => ['required'],
            'file.*' => ['file', 'mimes:pdf,doc,odt,xls,zip,docx,pptx,xlsx,ods,jpeg,png,jpg'],

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $files = $request->file;

        foreach ($files as $file) {

            $fileData = FileHelper::getFileData($file);

            $storage = FileHelper::saveFile($file, $fileData->name);

            FileHelper::saveToDb($request, $fileData, $storage->path);
        }

        // Add Notification event

        return response()->json(['message' => 'file sent !', 'url' => $storage->path], 200);
    }

    public function archiveFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'string'],

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->id;

        $file = Files::where('id', '=', $id)->first();

        if (!$file) {
            return response()->json(['status' => false, 'message' => 'file with the id:' . $id . ' does no exist'], 404);
        }

        $archiveFile = ArchivedFiles::create([
            'file_id' => $file->id,
            'archived_by' => Auth::id(),
            'file_name' => $file->file_name,
            'file_path' => $file->file_path,
            'file_size' => $file->file_size,
            'file_type' => $file->file_type,
            // 'archived_date' => now()
        ]);

        if (!$archiveFile) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['message' => 'file archived', 'id' => $id, 'archived_file_id' => $archiveFile->id], 200);
    }

    public function sentFiles()
    {
        return FileHelper::getFiles('shared_files');
    }

    public function receivedFiles()
    {

        return FileHelper::getFiles('received_files');
    }

    public function archivedFiles()
    {

        $archivedFiles = ArchivedFiles::where('archived_by', '=', Auth::id())->get();

        if (!$archivedFiles) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'data' => $archivedFiles], 200);
    }

    public function acceptFile(String $id)
    {
        return FileHelper::modifyFileStatus($id, status: 'accepted');
    }
    public function rejectFile(String $id)
    {
        return   FileHelper::modifyFileStatus($id, status: 'rejected');
    }

    public function deleteFile(String $id)
    {
        // $file =  Files::find($id);

        $delete = (new AdminApi())->assetsByAssetFolder(assetFolder: "e-registry/files");

        $file = "6LkfMjsHQ8yfor1QTy6G3PQ6H.ods";
        $delete = cloudinary()->search()->toUrl();


        return $delete;
    }
}
