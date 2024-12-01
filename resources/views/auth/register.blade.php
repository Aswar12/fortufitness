<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nama Lengkap') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
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

<div class="mt-4">
    <x-label for="password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
    <div class="relative">
        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-100 hover:text-gray-200" onclick="togglePassword('password_confirmation')">
            <span id="password-confirmation-eye" class="material-icons"></span>
        </button>
    </div>
</div>

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
            <div class="mt-4 ">
                <x-label for="gender" value="{{ __('Jenis Kelamin') }}" />
                <select id="gender" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="gender" required>
                    <option value="Perempuan">Perempuan</option>
                    <option value="Laki-laki">Laki-laki</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="birth_date" value="{{ __('Tanggal Lahir') }}" />
                <x-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" required />
            </div>

            <div class="mt-4">
                <x-label for="phone_number" value="{{ __('Nomor Telpon/Wa') }}" />
                <x-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" required />
            </div>

            <div class="mt-4">
                <x-label for="address" value="{{ __('Alamat') }}" />
                <textarea id="address" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" name="address" required></textarea>
            </div>

            <div class="mt-4">
                <x-label for="emergency_contact" value="{{ __('Kontak Darurat') }}" />
                <x-input id="emergency_contact" class="block mt-1 w-full" type="text" name="emergency_contact" required />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <x-label for="terms">
                    <div class="flex items-center">
                        <x-checkbox name="terms" id="terms" required />

                        <div class="ms-2">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </div>
                    </div>
                </x-label>
            </div>
            @endif
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Daftar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>