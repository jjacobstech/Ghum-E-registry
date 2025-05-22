<?php

use App\Models\Files;
use App\Livewire\Actions\Logout;
use function Livewire\Volt\state;

state([
    'receivedFiles' => fn() => Files::where('receiver_id', '=', Auth::id())->count(),
    'archivedFiles' => fn() => Files::where('receiver_id', '=', Auth::id())->where('archived', '=', true)->count(),

    'sentFiles' => fn() => Files::where('sender_id', '=', Auth::id())->count(),
]);
 $logout = function(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

?>
<div class="w-full relative space-y-20 py-6 transition-all duration-200">
    <div class="grid space-y-8">
        <a href="{{ route('dashboard') }}" wire:navigate
            @if (url()->current() === route('dashboard')) class="text-sm font-medium text-flag-green border-l-4 border-flag-green px-5 hover:text-grayish"
            @else
                class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green" @endif>
            Home

        </a>
        <span class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
            Department

        </span>
        <span class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
            Employees

        </span>
    </div>

    <div class="grid space-y-8">
        <span class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
            Received Files ({{ $receivedFiles }})

        </span>
        <span class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
            Shared Files ({{ $sentFiles }})
        </span>
        <span class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
            Archived files ({{ $archivedFiles }})
        </span>
    </div>

       <div class="grid space-y-8">
                        <span class="text-sm font-medium text-gray-500  px-5 hover:text-flag-green">
                            Settings
                        </span>
                        <span wire:click='logout' class="text-sm font-medium text-gray-500 px-5 hover:text-flag-green">
                            Logout

                        </span>
                    </div>
</div>
