<?php

namespace App\Helpers;

use App\Models\Files;
use Carbon\Traits\Timestamp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileHelper
{

    public static function getFileData(UploadedFile $file)
    {

        $originalFileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $fileName = time() . Str::random(25);

        return (object) ['name' => $originalFileName, 'filename' => $fileName . '.' . $fileExtension, 'extension' => $fileExtension, 'size' => $fileSize];
    }
    public static function saveFile(UploadedFile $file, $filename)
    {
        $storage = $file->storeAs(env('FILES_PATH') . $filename);

        return (object) ['path' => $storage];
    }

    public static function SaveToDb(Request $request, $fileData, $path)
    {

        $receiver = $request->receiver;
        $title = $request->title;
        $description = $request->description;
        $subject = $request->subject;
        $assignedTo = $request->assigned_to;
        $path = Storage::url($path);


        $saveFile = Files::create([
            'original_file_name' => $fileData->name,
            'file_name' => $fileData->filename,
            'file_size' => $fileData->size,
            'file_url' => $path,
            'file_type' => $fileData->extension,
            'title' => $title,
            'description' => $description,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver,
            'subject' => $subject,
            'dept_in_request' => Auth::user()->department,
            'assigned_to' => $assignedTo,
            'assigned_from' => Auth::user()->job_title,
        ]);

        if (!$saveFile) {
            return response()->json(['messsage' => 'server error'], 500);
        }

        return true;
    }

    public static function getFiles($fileType)
    {

        if ($fileType == 'shared_files') {
            $column = 'sender_id';
        } elseif ($fileType == 'received_files') {
            $column = 'receiver_id';
        }

        $Files = Files::where($column, '=', Auth::id())->get();

        if (!$Files) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'data' => $Files], 200);
    }

    public static function modifyFileStatus(String $id, $status, $comment = null)
    {

        $file = Files::where('id', '=', $id)->first();

        if (!$file) {
            return response()->json(['status' => false, 'error' => 'file does not exist'], 400);
        }

        if (!$comment) {
            $file->comment = $comment;
        }

        $file->status = $status;

        $saved = $file->save();

        if (!$saved) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'message' => 'file ' . $status], 200);
    }
}