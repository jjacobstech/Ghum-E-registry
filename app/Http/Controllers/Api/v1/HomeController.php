<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Files;
use App\Rules\Checkuser;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{


    public function dashboard()
    {
        $user = User::find(Auth::id());

        $receivedFiles = Files::where('receiver_id', '=', Auth::id())->count();

        $sentFiles = Files::where('sender_id', '=', Auth::id())->count();

        $newFiles = Files::where('receiver_id', '=', Auth::id())->where('', '=', Auth::id())->where('status', '=', 'action_required')->count();

        $rejectedFiles = Files::where('receiver_id', '=', Auth::id())->where('', '=', Auth::id())->where('status', '=', 'rejected')->count();

        $acceptedFiles = Files::where('receiver_id', '=', Auth::id())->where('', '=', Auth::id())->where('status', '=', 'accepted')->count();

        $approvedFiles = Files::where('receiver_id', '=', Auth::id())->where('', '=', Auth::id())->where('status', '=', 'approved')->count();

        $pendingFiles = Files::where('receiver_id', '=', Auth::id())->where('', '=', Auth::id())->where('status', '=', 'pending')->count();

        $archivedFiles = Files::where('receiver_id', '=', Auth::id())->where('archived', '=', true)->count();

        return response()->json([
            'user' => $user,
            'new_files' => $newFiles,
            'sent_files' => $sentFiles,
            'received_files' => $receivedFiles,
            'archived_files' => $archivedFiles,
            'accepted_files' => $acceptedFiles,
            'rejected_files' => $rejectedFiles,
            'approved_files' => $approvedFiles,
            'pending_files' => $pendingFiles,
            'files' => route('dashboard')
        ], 200);
    }
}