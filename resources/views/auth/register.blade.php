
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Register</h1>
    <form action="{{ route('register') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>
        <div class="mb-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
        </div>
        <div class="mb-4">
            <x-jet-label for="password" value="{{ __('Password') }}" />
            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        </div>
        <div class="mb-4">
            <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>
        <div class="mb-4">
            <x-jet-label for="gender" value="{{ __('Gender') }}" />
            <select id="gender" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" name="gender" required>
                <option value="Perempuan">Perempuan</option>
                <option value="Laki-laki">Laki-laki</option>
            </select>
        </div>
        <div class="mb-4">
            <x-jet-label for="birth_date" value="{{ __('Birth Date') }}" />
            <x-jet-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" required />
        </div>
        <div class="mb-4">
            <x-jet-label for="phone_number" value="{{ __('Phone Number') }}" />
            <x-jet-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" required />
        </div>
        <div class="mb-4">
            <x-jet-label for="address" value="{{ __('Address') }}" />
            <textarea id="address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" name="address" required></textarea>
        </div>
        <div class="mb-4">
            <x-jet-label for="emergency_contact" value="{{ __('Emergency Contact') }}" />
            <x-jet-input id="emergency_contact" class="block mt-1 w-full" type="text" name="emergency_contact" required />
        </div>
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mb-4">
            <x-jet-label for="terms">
                <div class="flex items-center">
                    <x-jet-checkbox name="terms" id="terms" required />
                    <div class="ml-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-jet-label>
        </div>
        @endif
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-jet-button class="ml-4">
                {{ __('Register') }}
            </x-jet-button>
        </div>
    </form>
</div>
@endsection
