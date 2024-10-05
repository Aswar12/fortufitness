
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Login</h1>
    <form action="{{ route('login') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>
        <div class="mb-4">
            <x-jet-label for="password" value="{{ __('Password') }}" />
            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        </div>
        <div class="mb-4 flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <x-jet-checkbox id="remember_me" name="remember" />
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-jet-button class="ml-4">
                {{ __('Login') }}
            </x-jet-button>
        </div>
    </form>
</div>
@endsection
