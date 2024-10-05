
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Login</h1>
    <form action="{{ route('login') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded mt-4">Login</button>
    </form>
</div>
@endsection
