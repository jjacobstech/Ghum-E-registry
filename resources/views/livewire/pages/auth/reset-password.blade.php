<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>



    <div class="w-full max-w-lg pt-10 mx-auto">
    <!-- Header -->
    <div class="mb-8 text-right text-gray-400">
        <p>YOUR</p>
        <p class="font-medium">Personal Info.</p>
    </div>

    <!-- Form Section -->
    <h1 class="mb-2 text-3xl font-bold text-gray-800">Register Your Account!</h1>
    <p class="mb-8 text-gray-500">We need your personal details to work better with you.</p>

    <form wire:submit.prevent='resetPassword'>
        @csrf
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

            </div>



                  <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full py-3 font-medium text-white transition duration-200 bg-green-800 rounded hover:bg-green-700">
            Reset
        </button>
    </form>

    <div class="flex items-center justify-end mt-4">
        <a class="text-gray-600 underline rounded-md text-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="{{ route('login') }}" wire:navigate>
            {{ __('Login') }}
        </a>



    </div>
</div>


