
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Card 1</h2>
            <p class="text-gray-600">This is a description for card 1.</p>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Card 2</h2>
            <p class="text-gray-600">This is a description for card 2.</p>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Card 3</h2>
            <p class="text-gray-600">This is a description for card 3.</p>
        </div>
    </div>
</div>
@endsection
