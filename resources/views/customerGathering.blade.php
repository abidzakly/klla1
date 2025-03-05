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

<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <nav class="bg-green-900 text-white w-full px-6 py-4 flex justify-between items-center shadow-md">
        <img src="{{ asset('image/logooo 1.png') }}" alt="Logo">

        <h1 class="text-xl font-bold mx-auto" style="font-size: 36px;">Monitoring Data DO dan SPK</h1>
    </nav>


    <div class="w-80flex flex-col space-y-4 mt-6">
        <div x-data="dropdownData()" class="bg-green-800 p-6 rounded-lg shadow-md overflow-x-auto relative">
            <div class="flex flex-col md:flex-row items-center md:justify-between h-auto md:h-20 relative">
                <h1
                    class="text-white font-semibold text-center text-4xl mt-4 md:mt-0 md:absolute md:inset-x-0 md:top-1/2 md:-translate-y-1/2">
                    Customer Gathering
                </h1>


                <div x-data="{ showCalendar: false }" class="relative md:w-auto">
                    <div @click="showCalendar = !showCalendar"
                        class="flex items-center w-48 mt-2 md:mt-0 md:absolute md:left-0 cursor-pointer">
                        <i class="fa-solid fa-calendar-days text-white ml-4 text-3xl"></i>
                        <p class="text-white font-bold text-xl ml-4">Sort By Date</p>
                    </div>

                    <div x-show="showCalendar" x-transition @click.outside="showCalendar = false"
                        class="absolute left-0 mt-10 bg-white p-4 rounded-lg shadow-lg z-50">
                        <input type="date" class="border border-gray-300 p-2 rounded-md w-full">
                    </div>
                </div>

            </div>





            <div class="flex flex-col" x-data="{
                openLocation: false,
                openTempat: false,
                selectedLocation: '{{ $detailTable->namaCabang }}',
                selectedTempat: '{{ $detailTable->namaTempat }}',
                selectedLocationId: '{{ $detailTable->idCabang }}', // Default ID for 'Bone'
                selectedTempatId: '{{ $detailTable->idTempat }}',   // Default ID for 'Mall'
                        tableData: {
                            'Bone': [{ 'id': 1, 'name': 'Bone' }],
                            'Soppeng': [{ 'id': 2, 'name': 'Soppeng' }],
                            'Sengkang': [{ 'id': 3, 'name': 'Sengkang' }],
                            'Kendari': [{ 'id': 4, 'name': 'Kendari' }],
                            'Tandean': [{ 'id': 5, 'name': 'Tandean' }],
                            'Kendari 3': [{ 'id': 6, 'name': 'Kendari 3' }],
                            'Kolaka': [{ 'id': 7, 'name': 'Kolaka' }],
                            'Bau Bau': [{ 'id': 8, 'name': 'Bau Bau' }],
                            'Palu Metadinata': [{ 'id': 9, 'name': 'Palu Metadinata' }],
                            'Palu Juanda': [{ 'id': 10, 'name': 'Palu Juanda' }],
                            'Poso': [{ 'id': 11, 'name': 'Poso' }],
                            'Luwuk Benggal': [{ 'id': 12, 'name': 'Luwuk Benggal' }],
                        },
                        tempatListData: {
                            'Mall': [{ 'id': 1, 'name': 'Mall' }],
                            'Swalayan': [{ 'id': 2, 'name': 'Swalayan' }],
                            'Pasar': [{ 'id': 3, 'name': 'Pasar' }],
                        },
                        searchTable(cabangId, tempatId) {
                            fetch(`/search-table/${cabangId}/${tempatId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.id) {
                                        window.location.href = `/publicDisplay/${data.id}`;
                                    } else {
                                        alert('Data not found');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                            }">

                <div class="flex justify-center">

                    <button @click="openLocation = !openLocation"
                        class="flex gap-1 text-white text-m bg-green-800 px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                        <p>Kalla Toyota</p>
                        <span x-text="selectedLocation"></span> ▼
                    </button>

                    <div x-show="openLocation" @click.away="openLocation = false"
                        class="absolute top-[90px] mt-2 w-48 bg-[#d9d9d9] shadow-lg transition-all duration-300 ease-in-out z-20 max-h-40 overflow-y-auto">
                        <template x-for="(tempatList, location) in tableData" :key="location">
                            <a href="#" @click.prevent="
                                    selectedLocation = location;
                                selectedLocationId = tableData[location][0].id;
                                openLocation = false;
                                searchTable(selectedLocationId, selectedTempatId);
                                " class="block px-4 py-2 font-bold text-gray-800 hover:bg-gray-200">
                                <div class="flex justify-between">
                                    <p x-text="location"></p>
                                    <i class="fa-regular fa-circle-check pt-1"></i>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>



                <div class="flex mt-4">
                    <div class="relative">
                        <button @click="openTempat = !openTempat"
                            class="w-32 text-white text-m bg-green-800 mr-4 px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                            <span x-text="selectedTempat"></span> ▼
                        </button>

                        <div x-show="openTempat" @click.away="openTempat = false"
                            class="absolute top-[40px] mt-2 w-48 bg-[#d9d9d9] shadow-lg transition-all duration-300 ease-in-out z-20">
                            <template x-for="(tempatList, tempat) in tempatListData" :key="tempat">
                                <a href="#" @click.prevent="
                                selectedTempat = tempat;
                        selectedTempatId = tempatListData[tempat][0].id;
                        openTempat = false;
                        searchTable(selectedLocationId, selectedTempatId);
                                " class="block px-4 py-2 font-bold text-gray-800 hover:bg-gray-200">
                                    <div class="flex justify-between">
                                        <p x-text="tempat"></p>
                                        <i class="fa-regular fa-circle-check pt-1"></i>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>



                    <div class="flex flex-col items-center justify-center">
                        <div class="flex flex-wrap md:flex-nowrap lg:flex-nowrap  justify-center gap-4">

                            <div id="tables-container ">
                                <table class="mr-4 base-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-white text-2xl ml-4 w-48 border-4 border-black">
                                                Leads
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-white text-xl ml-4 border-t-4 border-l-4 border-black w-20">
                                                Target
                                            </th>
                                            <th class="text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($detailTable->leads as $lead)
                                            <tr class="data-row">
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number" value=""
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $lead->target }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $lead->act }}
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
                                            <th colspan="2" class="text-white text-2xl ml-4 w-48 border-4 border-black">
                                                Prospek
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-white text-xl ml-4 border-t-4 border-l-4 border-black w-20">
                                                Target
                                            </th>
                                            <th class="text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($detailTable->prospek as $prospekz)
                                            <tr class="data-row">
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $prospekz->target }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $prospekz->act }}
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
                                            <th colspan="2" class="text-white text-2xl ml-4 w-48 border-4 border-black">
                                                Hot Prospek
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-white text-xl ml-4 border-t-4 border-l-4 border-black w-20">
                                                Target
                                            </th>
                                            <th class="text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                        </tr>



                                        @foreach ($detailTable->hotProspek as $hotProspekz)
                                            <tr class="data-row">
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $hotProspekz->target }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $hotProspekz->target }}
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
                                            <th colspan="2" class="text-white text-2xl ml-4 w-48 border-4 border-black">
                                                SPK
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-white text-xl ml-4 border-t-4 border-l-4 border-black w-20">
                                                Target
                                            </th>
                                            <th class="text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                        </tr>


                                        @foreach ($detailTable->spk as $spkz)
                                            <tr class="data-row">
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $spkz->target }}</p>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $spkz->act }}</p>
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
                                            <th colspan="2" class="text-white text-2xl ml-4 w-48 border-4 border-black">
                                                DO
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-white text-xl ml-4 border-t-4 border-l-4 border-black w-20">
                                                Target
                                            </th>
                                            <th class="text-white text-xl ml-4 border-r-4 border-l-4 border-black w-20">
                                                Act
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <tr class="data-row">
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                            <td
                                                class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                <div class="m-2 bg-white text-black rounded-md">
                                                    <input type="number"
                                                        class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                </div>
                                            </td>
                                        </tr>


                                        @foreach ($detailTable->spkDo as $spkDoz)
                                            <tr class="data-row">
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $spkDoz->target }}</p>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-white text-xl ml-4 border-r-4 border-l-4 border-b-4 border-black w-20">
                                                    <div class="m-2 bg-white text-black rounded-md">
                                                        <p type="number"
                                                            class="w-full border border-gray-300 rounded-lg paste-input px-1 text-center">
                                                            {{ $spkDoz->act }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        </div>

                        <button id="submit-button"
                            class="mt-4 bg-gray-200 text-black w-full py-2 rounded-xl hover:bg-gray-100 focus:outline-none transition-all duration-300 ease-in-out text-3xl font-bold">
                            Submit
                        </button>
                    </div>
                </div>
                <div class="w-full  bg-gray-200 rounded-full h-12 mt-6">
                    <div class="bg-green-500 h-12 rounded-full flex items-center justify-center w-2/5">
                        <span class="text-white font-bold text-lg">40%</span>
                    </div>
                </div>

            </div>



        </div>
    </div>



</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("tbody").forEach(tableBody => { // Select all table bodies
            const inputRow = tableBody.querySelector(".data-row"); // First input row

            tableBody.addEventListener("paste", function (event) {
                event.preventDefault(); // Prevent default paste behavior

                let clipboardData = event.clipboardData || window.clipboardData;
                let pastedData = clipboardData.getData("text");

                // Split data by new lines (rows) and tabs (columns)
                let rows = pastedData.trim().split("\n").map(row => row.split("\t"));

                // Find the first database-generated row (i.e., first <tr> after input row)
                let firstDatabaseRow = inputRow.nextElementSibling;

                rows.forEach((rowData) => {
                    let newRow = inputRow.cloneNode(true); // Clone input row for new data
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
                        tableBody.appendChild(newRow); // If no database rows exist, append
                    }
                });

                // Ensure the input row always remains at the top
                tableBody.prepend(inputRow);
            });
        });
    });





</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("submit-button").addEventListener("click", function () {
            let data = [];

            document.querySelectorAll(".data-row").forEach((row) => {
                let tableTitle = row.closest("table").querySelector("th").innerText.trim().toLowerCase();
                let subKategori = tableTitle.replace(/\s+/g, "_"); // Convert space to underscore
                let inputs = row.querySelectorAll("input");

                if (inputs.length < 2) return; // Ensure there are two input fields

                let target = inputs[0].value.trim();
                let act = inputs[1].value.trim();

                // Only include rows with at least one non-empty input
                if (target || act) {
                    data.push({
                        sub_kategori: subKategori,
                        target: target || null,
                        act: act || null,
                    });
                }
            });

            console.log("Collected Data:", data); // Debugging log
            const tabelUmumId = "{{ $detailTable->idTabelUmum }}"; // Ensure Blade processes this
            const dataPayload = { data: data }; // Wrap data properly

            fetch(`/submit-endpoint/${tabelUmumId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify(dataPayload),
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Server Response:", data); // Debugging log
                    alert(data.message);
                })
                .catch(error => console.error("Error:", error));
        });
    });

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#locationSelector').change(function () {
            let values = $(this).val().split(',');
            let cabangId = values[0];
            let tempatId = values[1];

            $.ajax({
                url: `/search-table/${cabangId}/${tempatId}`,
                method: 'GET',
                success: function (response) {
                    $('#result').html(response);
                },
                error: function () {
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

    function dropdownData() {
        return {
            openLocation: false,
            openTempat: false,
            selectedLocation: 'Pilih Cabang',
            selectedTempat: 'Pilih Tempat',
            cabangId: null, // Menyimpan ID cabang yang dipilih
            tableData: {}, // Data tabel akan diambil dari server

            fetchData(cabangId) {
                fetch(`/get-data/${cabangId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.tableData = data;
                        this.selectedTempat = data.tempat[0]?.name || "Pilih Tempat";
                    })
                    .catch(error => console.error("Error fetching data:", error));
            }
        };
    }

    // function dropdownData() {
    return {
        openLocation: false,
        openTempat: false,
        selectedLocation: 'Bone',  // Default selection
        selectedTempat: 'Mall',  // Default tempat
        tableData: {
            "Bone": [
                { "name": "Mall", "target": 89, "act": 41, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Swalayan", "target": 89, "act": 55, "target2": 120, "act2": 532, "target3": 894, "act3": 461, "target4": 859, "act4": 431, "target5": 819, "act5": 411, },
                { "name": "Pasar", "target": 122, "act": 68, "target2": 140, "act2": 52, "target3": 849, "act3": 41, "target4": 829, "act4": 441, "target5": 849, "act5": 451, }
            ],
            "Soppeng": [
                { "name": "Mall", "target": 146, "act": 98, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Swalayan", "target": 76, "act": 45, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Pasar", "target": 85, "act": 34, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, }
            ],
            "Kendari": [
                { "name": "Mall", "target": 102, "act": 70, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Swalayan", "target": 104, "act": 92, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Pasar", "target": 78, "act": 54, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, }
            ],
            "Sengkang": [
                { "name": "Mall", "target": 141, "act": 28, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Swalayan", "target": 62, "act": 21, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Pasar", "target": 129, "act": 49, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, }
            ],
            "Tandean": [
                { "name": "Mall", "target": 107, "act": 56, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Swalayan", "target": 92, "act": 39, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, },
                { "name": "Pasar", "target": 92, "act": 82, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41, }
            ],
            "Kendari 3": [
                { "name": "Mall", "target": 89, "act": 39, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 50, "act": 29, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 67, "act": 35, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Kolaka": [
                { "name": "Mall", "target": 104, "act": 41, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 115, "act": 104, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 92, "act": 84, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Bau Bau": [
                { "name": "Mall", "target": 89, "act": 57, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 75, "act": 57, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 119, "act": 65, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Pulau Matadinta": [
                { "name": "Mall", "target": 104, "act": 63, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 124, "act": 92, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 104, "act": 59, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Palu Juanda": [
                { "name": "Mall", "target": 52, "act": 23, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 81, "act": 33, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 73, "act": 22, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Poso": [
                { "name": "Mall", "target": 91, "act": 45, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 61, "act": 41, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 105, "act": 62, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ],
            "Luwuk Banggai": [
                { "name": "Mall", "target": 132, "act": 61, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Swalayan", "target": 128, "act": 60, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 },
                { "name": "Pasar", "target": 87, "act": 68, "target2": 10, "act2": 5, "target3": 89, "act3": 41, "target4": 89, "act4": 41, "target5": 89, "act5": 41 }
            ]
        }
    }


</script>




</html>
