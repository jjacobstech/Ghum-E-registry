<?php

use Rules\Password;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $department = '';
    public string $job_title = '';
    public string $agree = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Password::defaults()],
            'department' => ['required', 'string'],
            'job_title' => ['required', 'string'],
            'agree' => ['required', 'in:1'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<!-- Right Section (Form) -->

<div class="w-full max-w-lg mx-auto">
    <!-- Header -->
    <div class="mb-8 text-right text-gray-400">
        <p>YOUR</p>
        <p class="font-medium">Personal Info.</p>
    </div>

    <!-- Form Section -->
    <h1 class="mb-2 text-3xl font-bold text-gray-800">Register Your Account!</h1>
    <p class="mb-8 text-gray-500">We need your personal details to work better with you.</p>

    <form wire:submit.prevent='register'">
        @csrf

        <!-- Full Name -->
        <div class="mb-6">
            <label for="fullname" class="block mb-2 text-gray-700">Your fullname<span
                    class="text-red-500">*</span></label>
            <input id="fullname" type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                wire:model="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                placeholder="Invictus Innocent">
                 <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Department -->
        <div class="mb-6">
            <label for="department" class="block mb-2 text-gray-700">Department<span
                    class="text-red-500">*</span></label>
            <input id="department" type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                wire:model="department" value="{{ old('department') }}" required autocomplete="department" autofocus
                placeholder="Ministry Of Health">
                       <x-input-error :messages="$errors->get('department')" class="mt-1" />
        </div>

        <!-- Job Title -->
        <div class="mb-6">
            <label for="job_title" class="block mb-2 text-gray-700">Job title<span class="text-red-500">*</span></label>
            <input id="job_title" type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                wire:model="job_title" value="{{ old('job_title') }}" required
                placeholder="Enter your job title e.g head of admin">
                       <x-input-error :messages="$errors->get('job_title')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block mb-2 text-gray-700">Work email address<span
                    class="text-red-500">*</span></label>
            <input id="email" type="email"
                class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                wire:model="email" value="{{ old('email') }}" required autocomplete="email"
                placeholder="Enter your work email address">

                   <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block mb-2 text-gray-700">Create password<span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <input id="password" type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                    wire:model="password" required autocomplete="new-password" placeholder="Enter password">
                <button type="button" onclick="togglePasswordVisibility()"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5">
                    <span x-cloak="display:none" id="eyeopen" class="text-gray-500">@svg('solar-eye-bold', ['class' => 'w-5 h-5'])</span>
                    <span x-cloak="display:none" id="eyeclosed" class="text-gray-500">@svg('solar-eye-closed-bold', ['class' => 'w-5 h-5'])</span>
                </button>
            </div>

                  <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>


            <!-- Password Confirmation -->
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-gray-700">Confirm Password<span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <input id="password_confirmation" type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                    wire:model="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                <button type="button" onclick="toggleConfirmPasswordVisibility()"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5">
                    <span x-cloak="display:none" id="confirm_eyeopen" class="text-gray-500">@svg('solar-eye-bold', ['class' => 'w-5 h-5'])</span>
                    <span x-cloak="display:none" id="confirm_eyeclosed" class="text-gray-500">@svg('solar-eye-closed-bold', ['class' => 'w-5 h-5'])</span>
                </button>
            </div>

                  <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-center mb-8">
            <input type="checkbox" id="terms" wire:model="agree"
                class="w-5 h-5 text-green-800 border-gray-300 rounded focus:ring-green-600">
            <label for="terms" class="ml-2 text-gray-700">
                I agree to terms & conditions. Read terms & conditions <a href="#"
                    class="font-medium text-green-800">here</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full py-3 font-medium text-white transition duration-200 bg-green-800 rounded hover:bg-green-700">
            Register Account
        </button>
    </form>

    <div class="flex items-center justify-between mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="{{ route('login') }}" wire:navigate>
            {{ __('Login') }}
        </a>
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>
        @endif


    </div>
</div>



<script>
    var x = document.getElementById("password");
    var eyeopen = document.getElementById("eyeopen");
    var eyeclosed = document.getElementById("eyeclosed");
    eyeopen.style.display = "none";

    var y = document.getElementById("password_confirmation");
    var confirmEyeopen = document.getElementById("confirm_eyeopen");
    var confirmEyeclosed = document.getElementById("confirm_eyeclosed");
    confirmEyeopen.style.display = "none";

    function togglePasswordVisibility() {

        if (x.type === "password") {
            x.type = "text";
            eyeclosed.style.display = "none";
            eyeopen.style.display = "block";

        } else {
            x.type = "password";
            eyeopen.style.display = "none";
            eyeclosed.style.display = "block";
        }
    }
     function toggleConfirmPasswordVisibility() {

        if (y.type === "password") {
            y.type = "text";
            confirmEyeclosed.style.display = "none";
            confirmEyeopen.style.display = "block";

        } else {
            y.type = "password";
            confirmEyeopen.style.display = "none";
            confirmEyeclosed.style.display = "block";
        }
    }
</script>
