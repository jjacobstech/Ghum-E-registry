<?php

use App\Models\User;
use App\Models\Files;
use function Livewire\Volt\{layout, state};
layout('layouts.app');

state([
    'fileCategories' => [
     (object)   [
            'name' => 'New Files',
            'count' => Files::where('receiver_id', '=', Auth::id())->where('status', '=', 'action_required')->count(),
        ],

        (object) [
            'name' => 'New Rejected Files',
            'count'  => Files::where('receiver_id', '=', Auth::id())->where('status', '=', 'rejected')->count(),
        ],

        (object) [
            'name' => 'New Accepted Files',
            'count'  => Files::where('receiver_id', '=', Auth::id())->where('status', '=', 'accepted')->count(),
        ],

        (object) [
            'name' => 'Pending Files',
            'count'  => Files::where('receiver_id', '=', Auth::id())->where('status', '=', 'pending')->count(),
        ],
    ]
]);
?>

<div class="grid space-y-5">
    <div class="flex px-10 py-5 border border-gray-300 rounded-md justify-between">
        <div class="grid">
            <div class="font-normal text-gray-700">Share new file</div>
            <div class="text-sm text-gray-500">Share files easily with colleagues</div>
        </div>
        <div>
            <button class="py-2 px-5 bg-flag-green  rounded-md text-white hover:opacity-85">
                Share File
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-9">

        @foreach ($fileCategories as $fileCategory)
            <div class="bg-gray-100 p-6 rounded-xl hover:shadow-md transition-all duration-200">
                <span class="rounded-full   bg-black">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17 13.4V16.4C17 20.4 15.4 22 11.4 22H7.6C3.6 22 2 20.4 2 16.4V12.6C2 8.6 3.6 7 7.6 7H10.6"
                            stroke="#5F6D7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17 13.4H13.8C11.4 13.4 10.6 12.6 10.6 10.2V7L17 13.4Z" stroke="#5F6D7E"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.6 2H15.6" stroke="#5F6D7E" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M7 5C7 3.34 8.34 2 10 2H12.62" stroke="#5F6D7E" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M22 8V14.19C22 15.74 20.74 17 19.19 17" stroke="#5F6D7E" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M22 8H19C16.75 8 16 7.25 16 5V2L22 8Z" stroke="#5F6D7E" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="text-lg font-bold mb-2">{{ $fileCategory->count }}</h2>
                <p class="text-sm text-black">{{ $fileCategory->name }}</p>
            </div>
        @endforeach

    </div>
</div>
