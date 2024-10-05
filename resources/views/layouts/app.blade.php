
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FortuFitness</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-6 flex justify-between items-center">
                <div class="text-lg font-semibold">
                    <a href="{{ url('/') }}">FortuFitness</a>
                </div>
                <div>
                    <a href="#" class="text-gray-600 hover:text-gray-800 mx-2"><i class="fas fa-bell"></i></a>
                    <a href="#" class="text-gray-600 hover:text-gray-800 mx-2"><i class="fas fa-user"></i></a>
                </div>
            </div>
        </header>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="bg-gray-800 text-white w-64 p-6">
                <nav>
                    <a href="{{ url('/dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ url('/profile') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <a href="{{ url('/settings') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
