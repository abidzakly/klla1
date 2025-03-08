<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="flex flex-col items-center min-h-screen bg-gray-100">
    <nav class="items-center justify-between w-full px-6 py-4 text-white bg-green-900 shadow-md md:flex">
        <img src="{{ asset('image/logooo 1.png') }}" alt="Logo">
        <h3 class="mx-auto mt-4 text-xl font-bold md:mt-0" style="font-size: 36px;">Kalla East Area Department Tracking System</h1>
    </nav>
    <div class="flex flex-col items-center justify-center w-full max-w-6xl mt-6 space-y-6">
        <div class="flex justify-center gap-6">
            <a href="{{ route('monitoring.do.spk', ['id' => 1]) }}"
                class="flex items-center justify-center h-40 transition-all duration-300 ease-in-out bg-green-800 rounded-lg shadow-md w-60 hover:scale-105 group">
                <p
                    class="font-bold text-center text-white transition-all duration-300 ease-in-out pointer-events-none group-hover:scale-105">
                    Public Display
                </p>
            </a>
            <a href="{{ route('monitoring.do.spk', ['id' => 10]) }}"
                class="flex items-center justify-center h-40 transition-all duration-300 ease-in-out bg-green-800 rounded-lg shadow-md w-60 hover:scale-105 group">
                <p
                    class="font-bold text-center text-white transition-all duration-300 ease-in-out pointer-events-none group-hover:scale-105">
                    Customer Gathering
                </p>
            </a>
        </div>

        <div class="flex justify-center gap-6">
            <a href="{{ route('monitoring.do.spk', ['id' => 7]) }}"
                class="flex items-center justify-center h-40 transition-all duration-300 ease-in-out bg-green-800 rounded-lg shadow-md w-60 hover:scale-105 group">
                <p
                    class="font-bold text-center text-white transition-all duration-300 ease-in-out pointer-events-none group-hover:scale-105">
                    Digital Marketing
                </p>
            </a>
            <a href="{{ route('monitoring.do.spk', ['id' => 4]) }}"
                class="flex items-center justify-center h-40 transition-all duration-300 ease-in-out bg-green-800 rounded-lg shadow-md w-60 hover:scale-105 group">
                <p
                    class="font-bold text-center text-white transition-all duration-300 ease-in-out pointer-events-none group-hover:scale-105">
                    Grassroot
                </p>
            </a>
        </div>
    </div>






</body>

</html>
