<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @vite('resources/css/app.css')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            /* Adjust the width */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        ::-webkit-scrollbar-track {
            background-color: #d9d9d9;
        }
    </style>
</head>

<body class="flex flex-col items-center min-h-screen bg-gray-100">
    <nav class="flex items-center justify-between w-full px-6 py-4 text-white bg-green-900 shadow-md">
        <a href="{{ url('/') }}">
            <img src="{{ asset('image/logooo 1.png') }}" alt="Logo">
        </a>

        <h1 class="mx-auto text-xl font-bold" style="font-size: 36px;">Monitoring Data DO dan SPK</h1>
    </nav>


    <div class="flex-col mt-6 space-y-4 w-80flex computedClass" x-data="initData()" x-init="fetchData()">

        <div class="relative p-6 overflow-x-auto bg-green-800 rounded-lg shadow-md">
            <div class="relative flex flex-col items-center h-auto md:flex-row md:justify-between md:h-20">
                <h1
                    class="mt-4 text-4xl font-semibold text-center text-white md:mt-0 md:absolute md:inset-x-0 md:top-1/2 md:-translate-y-1/2">
                    {{ ucwords(str_replace('_', ' ', $detailTable->namaTabelUmum)) }}
                </h1>


                <div class="relative md:w-auto">
                    <div @click="showCalendar = !showCalendar"
                        class="flex items-center w-48 mt-2 cursor-pointer md:mt-0 md:absolute md:left-0">
                        <i class="ml-4 text-3xl text-white fa-solid fa-calendar-days"></i>
                        <p class="ml-4 text-xl font-bold text-white">Sort By Date</p>
                    </div>

                    <div x-show="showCalendar" x-transition @click.outside="showCalendar = false"
                        class="absolute left-0 z-50 p-4 mt-10 bg-white rounded-lg shadow-lg">
                        <input type="date" class="w-full p-2 border border-gray-300 rounded-md"
                            x-model="selectedDate" @change.prevent="searchTable(selectedLocationId, selectedTempatId)">
                    </div>

                </div>

            </div>

            <div class="flex flex-col">

                <div class="flex justify-center">

                    <button @click="openLocation = !openLocation"
                        class="flex gap-1 px-4 py-2 text-white bg-green-800 rounded-lg text-m hover:bg-green-600 focus:outline-none">
                        <p>Kalla Toyota</p>
                        <span x-text="selectedLocation"></span> ▼
                    </button>

                    <div x-show="openLocation" @click.away="openLocation = false"
                        class="absolute top-[90px] mt-2 w-48 bg-[#d9d9d9] shadow-lg transition-all duration-300 ease-in-out z-20 max-h-40 overflow-y-auto">
                        <template x-for="(tempatList, location) in tableData" :key="location">
                            <a href="#"
                                @click.prevent="                                
                                selectedLocation = location;                                   
                                selectedLocationId = tableData[location]['id'];
                                openLocation = false;
                                searchTable(selectedLocationId, selectedTempatId);
                                "
                                class="block px-4 py-2 font-bold text-gray-800 hover:bg-gray-200">
                                <div class="flex justify-between">
                                    <p x-text="location"></p>
                                    <i class="pt-1 fa-regular fa-circle-check"></i>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>



                <div class="flex mt-4">
                    <div class="relative">
                        <button @click="openTempat = !openTempat"
                            class="w-32 px-4 py-2 mr-4 text-white bg-green-800 rounded-lg text-m hover:bg-green-600 focus:outline-none">
                            <span x-text="selectedTempat"></span> ▼
                        </button>

                        <div x-show="openTempat" @click.away="openTempat = false"
                            class="absolute top-[40px] mt-2 w-48 bg-[#d9d9d9] shadow-lg transition-all duration-300 ease-in-out z-20">
                            <template x-for="(tempatList, tempat) in tempatListData" :key="tempat">
                                <a href="#"
                                    @click.prevent="
                                selectedTempat = tempat;   
                                console.log(tempatListData);                                                        
                                selectedTempatId = tempatListData[tempat]['id'];
                                openTempat = false;
                                searchTable(selectedLocationId, selectedTempatId);
                                "
                                    class="block px-4 py-2 font-bold text-gray-800 hover:bg-gray-200">
                                    <div class="flex justify-between">
                                        <p x-text="tempat"></p>
                                        <i class="pt-1 fa-regular fa-circle-check"></i>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>



                    <div class="flex flex-col items-center justify-center">
                        <div class="flex flex-wrap justify-center gap-4 md:flex-nowrap lg:flex-nowrap">

                            <div id="tables-container">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"
                                                class="w-48 ml-4 text-2xl text-white border-4 border-black">
                                                Leads
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="w-20 ml-4 text-xl text-white border-t-4 border-l-4 border-black">
                                                Target
                                            </th>
                                            <th class="w-20 ml-4 text-xl text-white border-l-4 border-r-4 border-black">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($detailTable->leads as $lead)
                                            <tr class="data-row" data-id="{{ $lead->id }}">
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $lead->target }}" oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $lead->act }}" oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="tables-container">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"
                                                class="w-48 ml-4 text-2xl text-white border-4 border-black">
                                                Prospek
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-t-4 border-l-4 border-black">
                                                Target
                                            </th>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-l-4 border-r-4 border-black">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($detailTable->prospek as $prospekz)
                                            <tr class="data-row" data-id="{{ $prospekz->id }}">
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $prospekz->target }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <p type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input">
                                                            <input type="number"
                                                                class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                                value="{{ $prospekz->act }}"
                                                                oninput="calculatePercentage()">
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            <div id="tables-container">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"
                                                class="w-48 ml-4 text-2xl text-white border-4 border-black">
                                                Hot Prospek
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-t-4 border-l-4 border-black">
                                                Target
                                            </th>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-l-4 border-r-4 border-black">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                        </tr>



                                        @foreach ($detailTable->hotProspek as $hotProspekz)
                                            <tr class="data-row" data-id="{{ $hotProspekz->id }}">
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $hotProspekz->target }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $hotProspekz->act }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            <div id="tables-container">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"
                                                class="w-48 ml-4 text-2xl text-white border-4 border-black">
                                                SPK
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-t-4 border-l-4 border-black">
                                                Target
                                            </th>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-l-4 border-r-4 border-black">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                        </tr>


                                        @foreach ($detailTable->spk as $spkz)
                                            <tr class="data-row" data-id="{{ $spkz->id }}">
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $spkz->target }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $spkz->act }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="tables-container">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"
                                                class="w-48 ml-4 text-2xl text-white border-4 border-black">
                                                DO
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-t-4 border-l-4 border-black">
                                                Target
                                            </th>
                                            <th
                                                class="w-20 ml-4 text-xl text-white border-l-4 border-r-4 border-black">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                            <td
                                                class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                <div class="m-2 text-black bg-white rounded-md">
                                                    <input type="number"
                                                        class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                        oninput="calculatePercentage()">
                                                </div>
                                            </td>
                                        </tr>


                                        @foreach ($detailTable->spkDo as $spkDoz)
                                            <tr class="data-row" data-id="{{ $spkDoz->id }}">
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $spkDoz->target }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                                <td
                                                    class="w-20 ml-4 text-xl text-white border-b-4 border-l-4 border-r-4 border-black">
                                                    <div class="m-2 text-black bg-white rounded-md">
                                                        <input type="number"
                                                            class="w-full px-1 text-center border border-gray-300 rounded-lg paste-input"
                                                            value="{{ $spkDoz->act }}"
                                                            oninput="calculatePercentage()">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        </div>

                        <button id="submit-button"
                            class="w-full py-2 mt-4 text-3xl font-bold text-black transition-all duration-300 ease-in-out bg-gray-200 rounded-xl hover:bg-gray-100 focus:outline-none">
                            Submit
                        </button>
                    </div>
                </div>
                <div class="w-full h-12 mt-6 bg-gray-200 rounded-full">
                    <div class="flex items-center justify-center h-12 bg-green-500 rounded-full w-14">
                        <span id="percentage" class="text-lg font-bold text-white">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("tbody").forEach(tableBody => { // Select all table bodies
            const inputRow = tableBody.querySelector(".data-row"); // First input row

            tableBody.addEventListener("paste", function(event) {
                event.preventDefault(); // Prevent default paste behavior

                let clipboardData = event.clipboardData || window.clipboardData;
                let pastedData = clipboardData.getData("text");

                // Split data by new lines (rows) and tabs (columns)
                let rows = pastedData.trim().split("\n").map(row => row.split("\t"));

                // Find the first database-generated row (i.e., first <tr> after input row)
                let firstDatabaseRow = inputRow.nextElementSibling;

                rows.forEach((rowData) => {
                    let newRow = inputRow.cloneNode(
                        true); // Clone input row for new data
                    let inputs = newRow.querySelectorAll(".paste-input");

                    // Fill the cloned row with pasted data
                    rowData.forEach((cellData, cellIndex) => {
                        if (inputs[cellIndex]) {
                            inputs[cellIndex].value = cellData.trim();
                        }
                    });

                    // Insert new rows below the input row but above database-generated rows
                    if (firstDatabaseRow) {
                        tableBody.insertBefore(newRow, firstDatabaseRow);
                    } else {
                        tableBody.appendChild(
                            newRow); // If no database rows exist, append
                    }
                });

                // Ensure the input row always remains at the top
                tableBody.prepend(inputRow);
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        calculatePercentage()
        document.getElementById("submit-button").addEventListener("click", function() {
            let data = [];

            document.querySelectorAll(".data-row").forEach((row) => {
                let tableTitle = row.closest("table").querySelector("th").innerText.trim()
                    .toLowerCase();
                let subKategori = tableTitle.replace(/\s+/g,
                    "_"); // Convert space to underscore
                let inputs = row.querySelectorAll("input");

                if (inputs.length < 2) return; // Ensure there are two input fields

                let target = inputs[0].value.trim();
                let act = inputs[1].value.trim();
                let id = row.dataset
                    .id; // Get the id from the data attribute

                let divElement = document.querySelector(".computedClass");
                const selectedDate = divElement.getAttribute("data-date");
                // Only include rows with at least one non-empty input
                data.push({
                    id: id || null,
                    sub_kategori: subKategori,
                    target: target || null,
                    act: act || null,
                    date: selectedDate,
                });
            });

            console.log("Collected Data:", data); // Debugging log
            const tabelUmumId = "{{ $detailTable->idTabelUmum }}"; // Ensure Blade processes this
            const dataPayload = {
                data: data
            }; // Wrap data properly

            fetch(`/submit-endpoint/${tabelUmumId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify(dataPayload),
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika response status bukan 200-299, lempar error manual
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json(); // Jika status 200, lanjutkan parsing JSON
                })
                .then(data => {
                    console.log("Server Response:", data);
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                        timer: 1500,
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    console.error("Error:", error);
                    Toast.fire({
                        icon: "error",
                        timer: 1500,
                        title: error.message || "Terjadi kesalahan",
                    });
                });

        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // attach date on data-date attribute .computedClass
        const divElement = document.querySelector(".computedClass");

        // Ambil tanggal dari query parameter atau gunakan tanggal sekarang dalam format YYYY-MM-DD
        const selectedDate = new URLSearchParams(window.location.search).get('date') || new Date().toISOString()
            .split('T')[0];

        // Set atribut data-date
        divElement.setAttribute("data-date", selectedDate);

        $('#locationSelector').change(function() {
            let values = $(this).val().split(',');
            let cabangId = values[0];
            let tempatId = values[1];

            $.ajax({
                url: `/search-table/${cabangId}/${tempatId}`,
                method: 'GET',
                success: function(response) {
                    $('#result').html(response);
                },
                error: function() {
                    $('#result').html('<p>Data not found</p>');
                }
            });
        });
    });
</script>

<script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     document.addEventListener("paste", function (event) {
    //         let activeInput = document.activeElement; // Get the currently focused input
    //         if (!activeInput || !activeInput.closest("table")) return; // Ensure it's inside a table

    //         let tableBody = activeInput.closest("table").querySelector("tbody"); // Find the correct tbody
    //         if (!tableBody) return;

    //         let activeCellIndex = Array.from(activeInput.closest("tr").children).indexOf(activeInput.closest("td")); // Get column index

    //         let clipboardData = event.clipboardData || window.clipboardData;
    //         let pastedText = clipboardData.getData("Text").trim();

    //         let rows = pastedText.split("\n").map(row => row.split("\t"));

    //         // Remove empty rows
    //         let filteredRows = rows.filter(row => row.some(cell => cell.trim() !== ""));

    //         if (filteredRows.length === 0) return; // Prevent empty paste

    //         filteredRows.forEach(row => {
    //             let newRow = document.createElement("tr");
    //             newRow.className = "data-row";

    //             // Ensure row has exactly 2 columns
    //             while (row.length < 2) {
    //                 row.push(""); // Fill missing columns
    //             }

    //             // Adjust order based on active column
    //             let adjustedRow = activeCellIndex === 1 ? ["", row[0]] : row;

    //             adjustedRow.forEach((cellData) => {
    //                 let cell = document.createElement("td");
    //                 cell.className = `text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20`;

    //                 let div = document.createElement("div");
    //                 div.className = "m-2 bg-white text-black rounded-md";

    //                 let input = document.createElement("input");
    //                 input.type = "number";
    //                 input.className = "w-full border border-gray-300 rounded-lg paste-input px-1 text-center";
    //                 input.value = cellData.trim() || ""; // Fill input with pasted data

    //                 div.appendChild(input);
    //                 cell.appendChild(div);
    //                 newRow.appendChild(cell);
    //             });

    //             tableBody.appendChild(newRow);
    //         });

    //         removeFirstRow(tableBody); // Remove first pasted row for this table
    //         updateRowBorders(tableBody); // Update borders for this table
    //         event.preventDefault();
    //     });

    //     function removeFirstRow(tableBody) {
    //         let rows = tableBody.querySelectorAll("tr");
    //         if (rows.length > 1) {  // Ensure at least one row remains
    //             rows[0].remove();
    //         }
    //     }

    //     function updateRowBorders(tableBody) {
    //         let rows = tableBody.querySelectorAll("tr");

    //         // Reset all row borders
    //         rows.forEach(row => {
    //             row.querySelectorAll("td").forEach(td => {
    //                 td.classList.remove("border-b-4");
    //             });
    //         });

    //         // Apply bottom, left, and right border only to the last row
    //         let lastRow = rows[rows.length - 1];
    //         if (lastRow) {
    //             lastRow.querySelectorAll("td").forEach(td => {
    //                 td.classList.add("border-b-4");
    //             });
    //         }
    //     }
    // });

    function initData() {
        return {
            openLocation: false,
            openTempat: false,
            showCalendar: false,
            selectedLocation: '{{ $detailTable->namaCabang }}',
            selectedTempat: '{{ $detailTable->namaTempat }}',
            selectedLocationId: '{{ $detailTable->idCabang }}',
            selectedTempatId: '{{ $detailTable->idTempat }}',
            tableData: {},
            tempatListData: {},
            selectedDate: new URLSearchParams(window.location.search).get('date') || '{{ now()->toDateString() }}',

            async fetchData() {
                try {
                    const response = await fetch(`/get-data/{{ $detailTable->idTabelUmum }}`);
                    const result = await response.json();

                    // get first on object value
                    this.tableData = result.cabang;
                    this.tempatListData = result.tempat;
                    this.selectedTempat = this.selectedTempat || "Pilih Tempat";
                    this.selectedTempatId = this.selectedTempatId || null;
                } catch (error) {
                    console.error("Error fetching data:", error);
                }
            },

            searchTable(cabangId, tempatId) {
                fetch(`/search-table/${cabangId}/${tempatId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.id) {
                            window.location.href = `/monitoring-do-&-spk/${data.id}?date=${this.selectedDate}`;
                        } else {
                            alert('Data not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching data');
                    });
            }
        };
    }


    function dropdownData() {
        return {
            openLocation: false,
            openTempat: false,
            selectedLocation: 'Bone', // Default selection
            selectedTempat: 'Mall', // Default tempat
            tableData: {
                "Bone": [{
                        "name": "Mall",
                        "target": 89,
                        "act": 41,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Swalayan",
                        "target": 89,
                        "act": 55,
                        "target2": 120,
                        "act2": 532,
                        "target3": 894,
                        "act3": 461,
                        "target4": 859,
                        "act4": 431,
                        "target5": 819,
                        "act5": 411,
                    },
                    {
                        "name": "Pasar",
                        "target": 122,
                        "act": 68,
                        "target2": 140,
                        "act2": 52,
                        "target3": 849,
                        "act3": 41,
                        "target4": 829,
                        "act4": 441,
                        "target5": 849,
                        "act5": 451,
                    }
                ],
                "Soppeng": [{
                        "name": "Mall",
                        "target": 146,
                        "act": 98,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Swalayan",
                        "target": 76,
                        "act": 45,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Pasar",
                        "target": 85,
                        "act": 34,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    }
                ],
                "Kendari": [{
                        "name": "Mall",
                        "target": 102,
                        "act": 70,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Swalayan",
                        "target": 104,
                        "act": 92,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Pasar",
                        "target": 78,
                        "act": 54,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    }
                ],
                "Sengkang": [{
                        "name": "Mall",
                        "target": 141,
                        "act": 28,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Swalayan",
                        "target": 62,
                        "act": 21,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Pasar",
                        "target": 129,
                        "act": 49,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    }
                ],
                "Tandean": [{
                        "name": "Mall",
                        "target": 107,
                        "act": 56,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Swalayan",
                        "target": 92,
                        "act": 39,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    },
                    {
                        "name": "Pasar",
                        "target": 92,
                        "act": 82,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41,
                    }
                ],
                "Kendari 3": [{
                        "name": "Mall",
                        "target": 89,
                        "act": 39,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 50,
                        "act": 29,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 67,
                        "act": 35,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Kolaka": [{
                        "name": "Mall",
                        "target": 104,
                        "act": 41,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 115,
                        "act": 104,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 92,
                        "act": 84,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Bau Bau": [{
                        "name": "Mall",
                        "target": 89,
                        "act": 57,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 75,
                        "act": 57,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 119,
                        "act": 65,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Pulau Matadinta": [{
                        "name": "Mall",
                        "target": 104,
                        "act": 63,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 124,
                        "act": 92,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 104,
                        "act": 59,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Palu Juanda": [{
                        "name": "Mall",
                        "target": 52,
                        "act": 23,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 81,
                        "act": 33,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 73,
                        "act": 22,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Poso": [{
                        "name": "Mall",
                        "target": 91,
                        "act": 45,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 61,
                        "act": 41,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 105,
                        "act": 62,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ],
                "Luwuk Banggai": [{
                        "name": "Mall",
                        "target": 132,
                        "act": 61,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Swalayan",
                        "target": 128,
                        "act": 60,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    },
                    {
                        "name": "Pasar",
                        "target": 87,
                        "act": 68,
                        "target2": 10,
                        "act2": 5,
                        "target3": 89,
                        "act3": 41,
                        "target4": 89,
                        "act4": 41,
                        "target5": 89,
                        "act5": 41
                    }
                ]
            }
        }
    }
</script>

<script>
    function animateProgress(targetPercentage) {
        let progressBar = document.querySelector(".bg-green-500");
        let percentageText = document.getElementById("percentage");

        let currentPercentage = parseInt(percentageText.innerText) || 0;
        let step = targetPercentage > currentPercentage ? 1 : -1; // Determine animation direction

        function updateProgress() {
            if ((step > 0 && currentPercentage < targetPercentage) ||
                (step < 0 && currentPercentage > targetPercentage)) {
                currentPercentage += step;
                if (currentPercentage < 10) {
                    progressBar.style.width = `5%`;
                } else {
                    progressBar.style.width = `${currentPercentage}%`;
                }
                percentageText.innerText = `${currentPercentage}%`;

                requestAnimationFrame(updateProgress);
            }
        }

        updateProgress();
    }

    function calculatePercentage() {
        let tables = document.querySelectorAll("#tables-container table");
        let totalPercentage = 0;
        let skipFirstRow = true; // Default: anggap semua row pertama kosong

        // Loop pertama: Cek apakah ada row 0 yang tidak kosong di salah satu tabel
        tables.forEach(table => {
            let rows = table.querySelectorAll(".data-row");
            if (rows.length === 0) return; // Jika tabel kosong, skip

            let firstRow = rows[0];
            let firstInputs = firstRow.querySelectorAll("input");
            let firstTarget = parseInt(firstInputs[0].value) || 0;
            let firstAct = parseInt(firstInputs[1].value) || 0;

            if (firstTarget !== 0 || firstAct !== 0) {
                skipFirstRow = false; // Jika ada yang terisi, jangan skip row 0
            }
        });

        // Loop kedua: Hitung persentase
        tables.forEach(table => {
            let rows = table.querySelectorAll(".data-row");
            let totalRows = rows.length;
            let validRows = 0;

            if (totalRows === 0) return; // Jika tabel kosong, langsung skip

            rows.forEach((row, index) => {
                if (skipFirstRow && index === 0) return; // Hanya skip row 0 jika semua kosong

                let inputs = row.querySelectorAll("input");
                let target = parseInt(inputs[0].value) || 0;
                let act = parseInt(inputs[1].value) || 0;

                if (act >= target && target > 0) {
                    validRows++;
                }
            });

            // Hitung jumlah baris yang dihitung (kurangi 1 jika row 0 di-skip)
            let countedRows = skipFirstRow ? totalRows - 1 : totalRows;

            // Hitung bobot untuk tabel ini (maks 20%)
            let tablePercentage = countedRows > 0 ? (validRows / countedRows) * 20 : 0;
            totalPercentage += tablePercentage;
        });

        // Pastikan total tidak lebih dari 100%
        totalPercentage = Math.min(totalPercentage, 100);
        animateProgress(totalPercentage);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", calculatePercentage);
        });
    });
</script>

</html>
