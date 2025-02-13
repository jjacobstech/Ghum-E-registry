<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Files;
use App\Rules\Checkuser;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Models\ArchivedFiles;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    public function shareFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'receiver' => ['required', 'string', new Checkuser],
            'file' => ['required'],
            'file.*' => ['file', 'mimes:pdf,doc,odt,xls,zip,docx,pptx,xlsx,ods,jpeg,png,jpg'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'assigned_to' => [' required ', ' string '],
            'subject' => [' required ', ' string '],

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $files = $request->file;

        foreach ($files as $file) {

            $fileData = FileHelper::getFileData($file);

            // if (Storage::exists('files/' . $fileData->filename)) {
            //     return response()->json(['message' => 'File Exists'], 409);
            // }
            $storage = FileHelper::saveFile($file, $fileData->filename);

            $saved =  FileHelper::saveToDb($request, $fileData, $storage->path);


            if ($saved) {
                return response()->json(['message' => 'File Sent'], 200);
            }
        }

        // Add Notification event

        // return response()->json(['message' => 'file sent !', 'url' => $storage->path], 200);
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

        $archiveFile = $file->update([
            'archived' => true
        ]);

        if (!$archiveFile) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['message' => 'file archived', 'id' => $id], 200);
    }
    public function sentFiles()
    {
        return FileHelper::getFiles('shared_files');
    }
    public function newFiles()
    {

        $newFiles = Files::where('status', '=', 'action_required')->get();

        if (!$newFiles) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'data' => $newFiles], 200);
    }
    public function pendingFiles()
    {

        $newFiles = Files::where('status', '=', 'pending')->get();

        if (!$newFiles) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'data' => $newFiles], 200);
    }
    public function receivedFiles()
    {
        return FileHelper::getFiles('received_files');
    }
    public function archivedFiles()
    {

        $archivedFiles = Files::where('archived', '=', true)->get();

        if (!$archivedFiles) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'data' => $archivedFiles], 200);
    }
    public function acceptFile(Request $request, $id = null)
    {
        $id = ($id) ? $id : $request->id;

        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }

        return FileHelper::modifyFileStatus($id, status: 'accepted');
    }
    public function approveFile(Request $request, $id = null)
    {

        $id = ($id) ? $id : $request->id;

        $comment = $request->comment;

        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }

        return  FileHelper::modifyFileStatus($id,  'approved', $comment);
    }
    public function rejectFile(Request $request, $id = null)
    {
        $id = ($id) ? $id : $request->id;

        $comment = $request->comment;

        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }

        return   FileHelper::modifyFileStatus($id, 'rejected', $comment);
    }
    public function deleteFile(Request $request, $id = null)
    {


        $id = ($id) ? $id : $request->id;

        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }
        $file =  Files::find($id);

        if (!$file) {
            return response()->json(['message' => 'file does not exist'], 409);
        }

        $delete = Storage::delete(config('app.uploads.files') . '/' . $file->file_name);

        if (!$delete) {
            return response()->json(['message' => 'server error'], 500);
        }

        $delete_record = $file->delete();

        if (!$delete_record) {
            return response()->json(['message' => 'server error'], 500);
        }
        return response()->json(['message' => 'file deleted'], 200);
    }
    public function reverseAction(Request $request, $id = null)
    {
        $id = ($id) ? $id : $request->id;

        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }
        if (empty($id)) {
            return response()->json(['message' => 'id cannot be empty'], 400);
        }

        return  FileHelper::modifyFileStatus($id, status: 'pending');
    }
}