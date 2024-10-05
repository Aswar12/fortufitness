
@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen bg-black bg-opacity-50">
    <!-- Header -->
    <header class="flex justify-between items-center p-4 bg-transparent text-white mx-4">
        <nav class="hidden md:flex space-x-4">
            <a class="hover:text-yellow-400" href="#">Home</a>
            <a class="hover:text-yellow-400" href="#">Workouts</a>
            <a class="hover:text-yellow-400" href="#">Trainers</a>
            <a class="hover:text-yellow-400" href="#">Contact</a>
        </nav>
        <div class="hidden md:flex items-center space-x-4">
            <img alt="User Profile" class="w-10 h-10 rounded-full object-cover golden-ratio" src="https://storage.googleapis.com/a1aa/image/Q9SasfP7s9zUHyAqFQU7dTZzKEs4Rs041W8Kp7ZhE093bzxJA.jpg"/>
        </div>
    </header>
    <!-- Mobile Profile Picture -->
    <div class="md:hidden flex justify-end p-4">
        <img alt="User Profile" class="w-10 h-10 rounded-full object-cover golden-ratio" src="https://storage.googleapis.com/a1aa/image/Q9SasfP7s9zUHyAqFQU7dTZzKEs4Rs041W8Kp7ZhE093bzxJA.jpg"/>
    </div>
    <!-- Mobile Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-black bg-opacity-40 text-white p-4 flex justify-around" id="mobile-menu">
        <a class="block py-2 hover:text-yellow-400 text-center" href="#">
            <i class="fas fa-home text-xl"></i>
            <span class="block text-xs">Home</span>
        </a>
        <a class="block py-2 hover:text-yellow-400 text-center" href="#">
            <i class="fas fa-dumbbell text-xl"></i>
            <span class="block text-xs">Workouts</span>
        </a>
        <a class="block py-2 hover:text-yellow-400 text-center" href="#">
            <i class="fas fa-users text-xl"></i>
            <span class="block text-xs">Trainers</span>
        </a>
        <a class="block py-2 hover:text-yellow-400 text-center" href="#">
            <i class="fas fa-envelope text-xl"></i>
            <span class="block text-xs">Contact</span>
        </a>
    </nav>
    <!-- Main Content -->
    <div class="flex justify-center items-center flex-grow">
        <div class="w-80 bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Add your content here -->
        </div>
    </div>
</div>
@endsection
