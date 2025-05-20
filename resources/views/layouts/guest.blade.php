<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex w-full sm:pt-0 bg-gray-100">

        <div class="flex min-h-screen bg-white w-full">
            <!-- Left Section (Green) -->
            <div class="relative hidden w-1/2 overflow-hidden bg-green-800 md:block">
                <!-- Logo -->
                <div class="p-8">
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7.75 13.5C7.06 13.5 6.5 12.94 6.5 12.25C6.5 11.56 7.06 11 7.75 11C8.44 11 9 11.56 9 12.25C9 12.94 8.44 13.5 7.75 13.5ZM12 13.5C11.31 13.5 10.75 12.94 10.75 12.25C10.75 11.56 11.31 11 12 11C12.69 11 13.25 11.56 13.25 12.25C13.25 12.94 12.69 13.5 12 13.5ZM16.25 13.5C15.56 13.5 15 12.94 15 12.25C15 11.56 15.56 11 16.25 11C16.94 11 17.5 11.56 17.5 12.25C17.5 12.94 16.94 13.5 16.25 13.5Z"
                                fill="currentColor" />
                        </svg>
                        <span class="text-xl font-bold text-white">ABSG</span>
                    </div>
                </div>

                <!-- Pattern Dots -->
                <div class="absolute top-1/3 right-1/4">
                    <div class="grid grid-cols-6 gap-2">
                        @for ($i = 0; $i < 36; $i++)
                            <div class="w-1 h-1 bg-orange-400 rounded-full"></div>
                        @endfor
                    </div>
                </div>

                <!-- Quote Section -->
                <div class="absolute max-w-md bottom-32 left-16">
                    <div class="mb-2 text-5xl font-bold text-green-300">"</div>
                    <p class="text-xl font-medium text-white">
                        talent wins games but teamwork and intelligence wins championships
                    </p>
                    <div class="flex items-center mt-6">
                        <p class="font-medium text-white">Micheal jordan</p>
                        <div class="p-1 ml-2 bg-green-600 rounded-full">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Corner graphic -->
                <div class="absolute bottom-0 right-0">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 4H4v16h16V4zm-4 4h-4v4h-4v4H4V4h12v4z" fill="white" />
                    </svg>
                </div>
            </div>

            <!-- Right Section (Form) -->
            <div class="flex justify-center w-full p-5 md:w-1/2">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
