<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Files;
use App\Rules\Checkuser;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\ArchivedFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{


    public function dashboard()
    {
        $user = User::find(Auth::id());

        $receivedFiles = Files::where('receiver_id', '=', Auth::id())->count();

        $sentFiles = Files::where('sender_id', '=', Auth::id())->count();

        $archivedFiles = ArchivedFiles::where('archived_by', '=', Auth::id())->count();
        // Ask if it is only the files you sent that can be archived or you can also archive other files sent to you.

        return response()->json([
            'user' => $user,
            'sent_files' => $sentFiles,
            'received_files' => $receivedFiles,
            'archived_files' => $archivedFiles
        ], 200);
    }
}
