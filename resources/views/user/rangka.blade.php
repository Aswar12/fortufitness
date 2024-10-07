<html>

<head>
    <title>Booking Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('https://storage.googleapis.com/a1aa/image/8m5zXIApxeWWLKkuekxu8hKQDaFF5hrkNmNIGV0egYG0vNHnA.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .golden-ratio {
            margin-top: -1rem;
            /* Adjusted to move the profile picture slightly down */
        }

        @media (min-width: 768px) {
            .desktop-margin {
                margin-top: 15px;
                /* Added margin-top for desktop view */
            }
        }
    </style>
</head>

<body class="bg-blue-200">
    <div class="flex flex-col min-h-screen bg-black bg-opacity-50">
        @yield('header')
        @yield('mobile-profile-picture')
        @yield('mobile-navigation')
        @yield('main-content')
    </div>
</body>

</html>