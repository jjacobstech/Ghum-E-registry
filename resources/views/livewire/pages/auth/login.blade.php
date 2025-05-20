<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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

                    <form wire:submit.prevent='login'">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block mb-2 text-gray-700">Work email address<span
                                    class="text-red-500">*</span></label>
                            <input id="email" type="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                wire:model="form.email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Enter your work email address">

                       <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block mb-2 text-gray-700">Create password<span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input id="password" type="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                wire:model="form.password" required autocomplete="new-password"
                                    placeholder="Enter password">
                                <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5">
                                    <span x-cloak="display:none" id="eyeopen" class="text-gray-500">@svg('solar-eye-bold', ['class' => 'w-5 h-5'])</span>
                                      <span x-cloak="display:none" id="eyeclosed" class="text-gray-500">@svg('solar-eye-closed-bold', ['class' => 'w-5 h-5'])</span>
                                </button>
                            </div>

                               <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-center mb-8">
                            <input type="checkbox" id="terms" name="terms"
                                class="w-5 h-5 text-green-800 border-gray-300 rounded focus:ring-green-600">
                            <label for="remember-me" class="ml-2 text-gray-700">
                               Remember Me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-3 font-medium text-white transition duration-200 bg-green-800 rounded hover:bg-green-700">
                            Login
                        </button>
                    </form>

                      <div class="flex items-center justify-between mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="{{ route('register') }}" wire:navigate>
            {{ __('Dont Have An Account?') }}
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
            </script>

</div>
