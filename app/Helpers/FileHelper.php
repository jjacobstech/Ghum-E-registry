<?php

namespace App\Helpers;

use App\Models\Files;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        $fileName = Str::random(25);

        return (object) ['name' => $fileName, 'originalFileName' => $originalFileName, 'extension' => $fileExtension, 'size' => $fileSize];
    }
    public static function saveFile(UploadedFile $file, $filename)
    {

        $storage = cloudinary()->upload($file->getRealPath(), ['folder' => 'e-registry/files/', 'resource_type' => 'raw', 'public_id' => $filename])->getSecurePath();

        if (!$storage) {
            return response()->json(['messsage' => 'server error'], 500);
        }
        if (Storage::exists($storage)) {
            return response()->json(['messsage' => 'file exists'], 403);
        }

        return (object) ['path' => $storage];
    }

    public static function SaveToDb(Request $request, $fileData, $path)
    {

        $receiver = $request->receiver;
        $title = $request->title;
        $description = $request->description;
        $subject = $request->subject;
        $assignedTo = $request->assigned_to;


        $saveFile = Files::create([
            'original_file_name' => $fileData->originalFileName,
            'file_name' => $fileData->name,
            'file_size' => $fileData->size,
            'file_path' => $path,
            'file_type' => $fileData->extension,
            'title' => $title,
            'description' => $description,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver,
            'subject' => $subject,
            'dept_in_request' => Auth::user()->department,
            'assigned_to' => $assignedTo,

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

    public static function modifyFileStatus(String $id, $status)
    {

        $file = Files::where('id', '=', $id)->first();

        if (!$file) {
            return response()->json(['status' => false, 'error' => 'file does not exist'], 400);
        }

        $file->status = $status;

        $saved = $file->save();

        if (!$saved) {
            return response()->json(['status' => false, 'error' => 'server error'], 500);
        }

        return response()->json(['status' => true, 'messager' => 'file ' . $status], 200);
    }
}