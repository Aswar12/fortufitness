<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        @session('status')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ $value }}
        </div>
        @endsession
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <div class="mt-4">
                <x-label for="password" value="{{ __('Kata Sandi') }}" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-100 hover:text-gray-200" onclick="togglePassword('password')">
                        <span id="password-eye" class="material-icons"></span>
                    </button>
                </div>
            </div>
            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
        <div class="mt-4 text-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Belum punya akun?') }}</span>
            <a href="{{ route('register') }}" class="underline text-sm text-yellow-500 hover:text-yellow-600">
                {{ __('Daftar di sini') }}
            </a>
        </div>
    </x-authentication-card>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = inputId === 'password' ? document.getElementById('password-eye') : document.getElementById('password-confirmation-eye');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.textContent = 'visibility_off'; // Change icon to "visibility_off"
            } else {
                input.type = 'password';
                eyeIcon.textContent = 'visibility'; // Change icon back to "visibility"
            }
        }
    </script>
</x-guest-layout>