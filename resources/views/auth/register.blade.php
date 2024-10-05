
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Register</h1>
    <form action="{{ route('register') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mt-4">
            <label for="gender" class="block text-gray-700">Gender</label>
            <select id="gender" class="w-full p-2 border border-gray-300 rounded mt-1" name="gender" required>
                <option value="Perempuan">Perempuan</option>
                <option value="Laki-laki">Laki-laki</option>
            </select>
        </div>
        <div class="mt-4">
            <label for="birth_date" class="block text-gray-700">Birth Date</label>
            <input type="date" id="birth_date" name="birth_date" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mt-4">
            <label for="phone_number" class="block text-gray-700">Phone Number</label>
            <input type="tel" id="phone_number" name="phone_number" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mt-4">
            <label for="address" class="block text-gray-700">Address</label>
            <textarea id="address" name="address" class="w-full p-2 border border-gray-300 rounded mt-1" required></textarea>
        </div>
        <div class="mt-4">
            <label for="emergency_contact" class="block text-gray-700">Emergency Contact</label>
            <input type="text" id="emergency_contact" name="emergency_contact" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
            <label for="terms" class="block text-gray-700">
                <div class="flex items-center">
                    <input type="checkbox" name="terms" id="terms" required>
                    <div class="ml-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </label>
        </div>
        @endif
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button type="submit" class="ml-4 bg-blue-500 text-white p-2 rounded">Register</button>
        </div>
    </form>
</div>
@endsection
