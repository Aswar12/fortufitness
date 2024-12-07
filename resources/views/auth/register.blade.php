<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nama Lengkap') }}" />
                <x-input id="name" class="block mt-1 w-full @error('name') border-red-500 @enderror" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full @error('email') border-red-500 @enderror" type="email" name="email" :value="old('email')" required autocomplete="username" />
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Kata Sandi') }}" />
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full @error('password') border-red-500 @enderror" type="password" name="password" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-100 hover:text-gray-200" onclick="togglePassword('password')">
                        <span id="password-eye" class="material-icons"></span>
                    </button>
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
                <div class="relative">
                    <x-input id="password_confirmation" class="block mt-1 w-full @error('password_confirmation') border-red-500 @enderror" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-100 hover:text-gray-200" onclick="togglePassword('password_confirmation')">
                        <span id="password-confirmation-eye" class="material-icons"></span>
                    </button>
                </div>
                @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="gender" value="{{ __('Jenis Kelamin') }}" />
                <select id="gender" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full @error('gender') border-red-500 @enderror" name="gender" required>
                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                </select>
                @error('gender')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="birth_date" value="{{ __('Tanggal Lahir') }}" />
                <x-input id="birth_date" class="block mt-1 w-full @error('birth_date') border-red-500 @enderror" type="date" name="birth_date" required />
                @error('birth_date')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="phone_number" value="{{ __('Nomor Telpon/Wa') }}" />
                <x-input id="phone_number" class="block mt-1 w-full @error('phone_number') border-red-500 @enderror" type="number" name="phone_number" required />
                @error('phone_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="address" value="{{ __('Alamat') }}" />
                <textarea id="address" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full @error('address') border-red-500 @enderror" name="address" required></textarea>
                @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="emergency_contact" value="{{ __('Kontak Darurat') }}" />
                <x-input id="emergency_contact" class="block mt-1 w-full @error('emergency_contact') border-red-500 @enderror" type="number" name="emergency_contact" required />
                @error('emergency_contact')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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
                @error('terms')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Sudah punya akun?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Daftar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = inputId === 'password' ? document.getElementById('password-eye') : document.getElementById('password-confirmation-eye');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.textContent = 'visibility_off'; // Ikon mata tertutup
            } else {
                input.type = 'password';
                eyeIcon.textContent = 'visibility'; // Ikon mata terbuka
            }
        }

        // Validasi real-time untuk nomor telepon
        document.getElementById('phone_number').addEventListener('input', function(e) {
            // Hapus karakter non-digit
            this.value = this.value.replace(/[^0-9]/g, '');

            // Batasi panjang maksimal
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });

        // Validasi tanggal lahir
        const birthDateInput = document.getElementById('birth_date');
        const today = new Date();
        const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
        const maxDate = new Date(today.getFullYear() - 13, today.getMonth(), today.getDate());

        birthDateInput.min = minDate.toISOString().split('T')[0];
        birthDateInput.max = maxDate.toISOString().split('T')[0];
    </script>
</x-guest-layout>