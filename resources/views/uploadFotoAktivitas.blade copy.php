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

    <div class="flex flex-col items-center justify-center w-full bg-gray-100">
        <div class="flex justify-center items-center min-h-[800px] w-full">
            <div class="mx-7 min-w-[90%] h-[600px] p-5 pl-8 text-2xl font-bold text-white bg-green-800 rounded-lg shadow-md"
                x-data="initData()" x-init="fetchData()">

                <div class="relative flex">
                    <div class="relative flex-1">
                        <div class="flex flex-col items-center h-auto md:flex-row md:justify-between md:h-20">
                            <div>
                                <div @click="showCalendar = !showCalendar"
                                    class="flex items-center w-48 mt-2 cursor-pointer">
                                    <i class="ml-4 text-3xl text-white fa-solid fa-calendar-days"></i>
                                    <p class="ml-4 text-xl font-bold text-white">Sort By Date</p>
                                </div>
                                <div x-show="showCalendar" x-transition @click.outside="showCalendar = false"
                                    class="absolute left-0 z-50 p-4 mt-5 bg-white rounded-lg shadow-lg">
                                    <input type="date" class="w-full p-2 border border-gray-300 rounded-md"
                                        x-model="selectedDate"
                                        @change.prevent="searchTable(selectedLocationId, selectedTempatId)">
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-0 pointer-events-none">

                            {{-- Tabel umum --}}
                            <h1 class="text-4xl font-semibold text-center text-white pointer-events-auto">
                                {{ ucwords(str_replace('_', ' ', $detailTable->namaTabelUmum)) }}
                            </h1>

                            {{-- select cabang --}}
                            <div class="relative flex justify-center w-full">
                                <button @click="openLocation = !openLocation"
                                    class="flex gap-1 px-4 py-2 text-white bg-green-800 rounded-lg text-m hover:bg-green-600 focus:outline-none">
                                    <p>Kalla Toyota</p>
                                    <span x-text="selectedLocation"></span> ▼
                                </button>

                                <div x-show="" @click.away="openLocation = false"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

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
</script>

</html>
