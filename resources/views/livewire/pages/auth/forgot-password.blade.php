<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Right Section (Form) -->

    <div class="w-full max-w-lg mx-auto">


        <!-- Form Section -->
        <h1 class="mb-2 text-3xl font-bold text-gray-800">Password Reset</h1>
        <p class="mb-8 text-gray-500">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}.
        </p>

        <form wire:submit="sendPasswordResetLink">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block mb-2 text-gray-700">Work email address<span
                        class="text-red-500">*</span></label>
                <input id="email" type="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent"
                    wire:model="email"  required autocomplete="email"
                    placeholder="Enter your work email address">

                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>





            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 font-medium text-white transition duration-200 bg-green-800 rounded hover:bg-green-700">
                Send Reset Link
            </button>
        </form>

        <div class="flex items-center justify-end mt-4">

                <a class="underline text-md text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}" wire:navigate>
                    {{ __('Login') }}
                </a>


        </div>
    </div>
</div>
