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

<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <nav class="bg-green-900 text-white w-full px-6 py-4 md:flex justify-between items-center shadow-md">
        <img src="{{ asset('image/logooo 1.png') }}" alt="Logo">
        <h3 class="text-xl font-bold mx-auto mt-4 md:mt-0" style="font-size: 36px;">Kalla East Area Department Tracking System</h1>
    </nav>
    <div class="w-full max-w-6xl flex flex-col items-center justify-center space-y-6 mt-6">
        <div class="flex justify-center gap-6">
            <a href="{{ route('public.display', ['id' => 1]) }}"
                class="bg-green-800 w-60 h-40 rounded-lg shadow-md flex items-center justify-center transition-all duration-300 ease-in-out hover:scale-105 group">
                <p
                    class="text-white font-bold text-center pointer-events-none transition-all duration-300 ease-in-out group-hover:scale-105">
                    Public Display
                </p>
            </a>
            <a href="{{ route('customer.gathering', ['id' => 10]) }}"
                class="bg-green-800 w-60 h-40 rounded-lg shadow-md flex items-center justify-center transition-all duration-300 ease-in-out hover:scale-105 group">
                <p
                    class="text-white font-bold text-center pointer-events-none transition-all duration-300 ease-in-out group-hover:scale-105">
                    Customer Gathering
                </p>
            </a>
        </div>

        <div class="flex justify-center gap-6">
            <a href="{{ route('digital.marketing', ['id' => 7]) }}"
                class="bg-green-800 w-60 h-40 rounded-lg shadow-md flex items-center justify-center transition-all duration-300 ease-in-out hover:scale-105 group">
                <p
                    class="text-white font-bold text-center pointer-events-none transition-all duration-300 ease-in-out group-hover:scale-105">
                    Digital Marketing
                </p>
            </a>
            <a href="{{ route('customer.gathering', ['id' => 4]) }}"
                class="bg-green-800 w-60 h-40 rounded-lg shadow-md flex items-center justify-center transition-all duration-300 ease-in-out hover:scale-105 group">
                <p
                    class="text-white font-bold text-center pointer-events-none transition-all duration-300 ease-in-out group-hover:scale-105">
                    Grassroot
                </p>
            </a>
        </div>
    </div>






</body>

</html>
