<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Target hanya input number dalam DataTables */
        .dataTables_wrapper input[type="number"]::-webkit-outer-spin-button,
        .dataTables_wrapper input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .dataTables_wrapper {
            color: white !important;
        }

        .dataTables_wrapper input[type="number"] {
            -moz-appearance: textfield;
            /* Firefox */
        }

        /* Hilangkan style background putih default dari DataTables */
        .dataTables_wrapper table.dataTable tbody tr,
        .dataTables_wrapper table.dataTable tbody td {
            background-color: transparent !important;
            /* Gunakan transparent agar warna custom bisa diterapkan */
            /* border: 1px solid #d1d5db; */
            /* Tetap tampilkan border */
            color: white !important;
            /* Set text color to white */
        }

        /* Warna teks "Showing X of Y entries" */
        .dataTables_info {
            color: white !important;
        }

        /* Warna teks dalam pagination */
        .dataTables_paginate .paginate_button {
            color: white !important;
        }

        /* Warna teks pagination yang aktif */
        .dataTables_paginate .paginate_button.current {
            background-color: white !important;
            color: black !important;
        }

        .paginate_button previous:not(.disabled):hover,
        .paginate_button next:not(.disabled):hover {
            background-color: white !important;
            color: black !important;
        }

        .dataTables_wrapper .dataTables_length {
            color: white !important;
            margin-bottom: 1rem;
        }

        .dataTables_filter {
            color: white !important;
        }

        #data-table_wrapper {
            width: 100%;
            max-height: 700px;
            /* Batas maksimal lebar tabel */
            overflow-x: auto;
            /* Scroll horizontal */
        }

        #data-table th,
        #data-table td {
            border: none;
            padding: 8px;
            text-align: center;
        }

        .editable-textarea {
            width: 100%;
            /* height: 100px; */
            border: 1px solid #ccc;
            resize: none;
            color: white;
            background-color: transparent;
        }

        .editable-textarea.edit-mode {
            border: 1px solid #d1d5db;
            /* Border for edit mode */
        }

        .editable-textarea[readonly] {
            border: none;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            /* Membuat ikon menjadi putih */
        }

        table.dataTable td.dataTables_empty {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
            /* tinggi bebas sih, sesuai tampilan */
            text-align: center;
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


    <div class="flex flex-col mt-6 space-y-4 w-[90%] computedClass" x-data="initData()" x-init="fetchData()">
        <!-- Hidden input untuk tabel_umum_id dinamis -->
        <input type="hidden" id="selectedTabelUmumId" value="{{ $detailTable->idTabelUmum }}">
        <!-- Hidden input untuk selectedDate dinamis -->
        <input type="hidden" id="selectedDateHidden" value="{{ request('date', now()->toDateString()) }}">
        <!-- Hidden input untuk cabang_id (agar dikirim ke backend saat upload) -->
        <input type="hidden" id="selectedCabangId" value="{{ $detailTable->idCabang }}">
        <div class="relative p-6 overflow-x-auto bg-green-800 rounded-lg shadow-md">
            <div class="relative flex flex-col items-center h-auto md:flex-row md:justify-between md:h-20">

                <div class="relative md:w-auto z-[9]">
                    <div @click="showCalendar = !showCalendar"
                        class="flex items-center w-48 mt-2 cursor-pointer md:mt-0 md:absolute md:left-0">
                        <i class="ml-4 text-3xl text-white fa-solid fa-calendar-days"></i>
                        <p class="ml-4 text-xl font-bold text-white">Sort By Date</p>
                    </div>

                    <div x-show="showCalendar" x-transition @click.outside="showCalendar = false"
                        class="absolute left-0 p-4 mt-10 bg-white rounded-lg shadow-lg z-4">
                        <input type="date" class="w-full p-2 border border-gray-300 rounded-md"
                            x-model="selectedDate"
                            @change.prevent="
                                document.getElementById('selectedDateHidden').value = selectedDate;
                                onFilterChange();
                            "
                            onkeydown="return false;" style="background-color: white; cursor: pointer;" />
                    </div>

                </div>

                <div class="relative w-full h-20 z-[7]">
                    <div
                        class="absolute flex items-center justify-center -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                        <!-- Dropdown Trigger -->
                        <button @click="openPhotoActivity = !openPhotoActivity"
                            class="flex items-center gap-2 text-4xl font-semibold text-white">
                            <span x-text="selectedPhotoActivityText"></span>
                            <span>▼</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="openPhotoActivity" @click.away="openPhotoActivity = false"
                            class="absolute top-full mt-2 w-64 bg-[#d9d9d9] shadow-lg rounded max-h-40 overflow-y-auto text-2xl font-semibold text-gray-800">
                            <template x-for="item in photoActivityList" :key="item.id">
                                <a href="#" @click.prevent="selectPhotoActivity(item)"
                                    class="block px-4 py-2 hover:bg-gray-200">
                                    <div class="flex justify-between">
                                        <span x-text="item.text"></span>
                                        <i class="pt-1 fa-regular fa-circle-check"
                                            x-show="selectedTabelUmumId == item.id"></i>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="absolute flex items-center right-10 z-[10]">
                    <div>
                        <label for="imageUpload"
                            class="flex items-center gap-2 ml-auto text-lg font-bold text-white cursor-pointer">
                            <span class="text-2xl">Foto</span>
                            <i class="text-2xl fa-solid fa-plus"></i>
                            <input type="file" id="imageUpload" class="hidden" accept=".jpg,.jpeg,.png,.svg,"
                                multiple>

                        </label>
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
                        <template x-for="(id, location) in tableData" :key="location">
                            <a href="#" @click.prevent="selectLocation(location)"
                                class="block px-4 py-2 font-bold text-gray-800 hover:bg-gray-200">
                                <div class="flex justify-between">
                                    <p x-text="location"></p>
                                    <i class="pt-1 fa-regular fa-circle-check"></i>
                                </div>
                            </a>
                        </template>
                    </div>


                </div>


                {{-- @if ($photoActivities == 0)
                    <div id="child-drop-area"
                        class="relative flex flex-col items-center justify-center w-full gap-4 p-10 mt-3 text-3xl font-bold text-white cursor-pointer focus:outline-none">
                        <i class="text-white fa-regular fa-file" style="font-size:56px;"></i>
                        <p>Tarik dan Lepaskan File Disini</p>
                        <p>atau</p>
                        <p>gunakan tombol "+"</p>

                        <input type="file" id="file-input" class="hidden" accept=".jpg,.jpeg,.png,.svg," multiple>
                    </div>
                @else
                    <div class="w-full mt-4 overflow-x-auto rounded-lg" id="child-drop-area">
                        <table id="data-table" class="w-full text-center text-black" style="border: none">
                            <thead>
                                <tr class="">
                                    <th class="w-full px-2 py-2 border-2 border-none"></th>
                                </tr>
                            </thead>
                        </table>
                        <input type="file" id="file-input" class="hidden" accept=".jpg,.jpeg,.png,.svg," multiple>
                    </div>
                @endif --}}

                <div class="w-full mt-4 overflow-x-auto rounded-lg" id="child-drop-area">
                    <table id="data-table" class="w-full text-center text-black min-h-[400px]" style="border: none">
                        <thead>
                            <tr class="">
                                <th class="w-full px-2 py-2 border-2 border-none"></th>
                            </tr>
                        </thead>
                    </table>
                    <input type="file" id="file-input" class="hidden" accept=".jpg,.jpeg,.png,.svg," multiple>
                </div>


                {{-- <button id="submit-button"
                    class="w-full py-2 mt-4 text-3xl font-bold text-black transition-all duration-300 ease-in-out bg-gray-200 rounded-xl hover:bg-gray-100 focus:outline-none">
                    Submit
                </button> --}}
            </div>
        </div>
    </div>

    <!-- Modal untuk input detail file -->
    <div id="fileModal" tabindex="-1"
        class="fixed inset-0 flex items-center justify-center hidden bg-opacity-50 z-[11] bg-black/40 backdrop-blur-sm">
        <div class="p-4 bg-white rounded-lg w-96">
            <h2 class="mb-4 text-lg font-bold">Detail File</h2>
            <form id="fileForm" enctype="multipart/form-data">
                <div id="fileDetailsContainer" class="space-y-4 max-h-[40vh] overflow-y-auto"></div>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" onclick="closeFileModal()"
                        class="px-4 py-2 text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                        Submit dan Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk pratinjau gambar -->
    <div id="previewModal" tabindex="-1"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 backdrop-blur-sm">
        <div class="p-4 bg-white rounded-lg w-96">
            <img id="previewImage" src="" alt="Preview" class="w-full h-auto rounded">
            <div class="flex justify-end mt-3">
                <button onclick="closePreviewModal()" class="px-4 py-2 text-white bg-red-500 rounded">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Info/Edit File -->
    <div id="infoModal" tabindex="-1"
        class="fixed inset-0 z-40 flex items-center justify-center hidden bg-opacity-50 bg-black/40 backdrop-blur-sm">
        <div class="relative p-4 bg-white rounded-lg w-96">
            <h2 class="mb-2 text-lg font-bold">Detail File</h2>
            <form id="infoForm" enctype="multipart/form-data">
                <div id="infoDetailsContainer" class="max-h-[40vh] overflow-y-auto">
                    <!-- Parent: View/Edit Component -->
                    <div id="info-view-block" class="">
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-blue-600 underline truncate cursor-pointer preview-link hover:underline">
                                <span>File :</span>
                                <span id="info-view-file" class="truncate"></span>
                            </h3>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Event</label>
                            <p id="info-view-event"></p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Lokasi Event</label>
                            <p id="info-view-location"></p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Caption</label>
                            <p id="info-view-caption"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Event</label>
                            <p id="info-view-date"></p>
                        </div>
                    </div>
                    <div id="info-edit-block" class="flex flex-col hidden gap-4">
                        <div>
                            <h3
                                class="mb-2 text-lg font-semibold text-blue-600 underline truncate cursor-pointer preview-link hover:underline">
                                <span>File : </span>
                                <span id="info-edit-file" class="truncate"></span>
                            </h3>
                            <div class="">
                                <span>Ganti file (optional): </span>
                                <input type="file" name="file" accept=".jpg,.jpeg,.png,.svg"
                                    class="block mt-3 mb-3" />
                                <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama File Photo</label>
                            <input type="text" name="photo_activity_file_name"
                                class="block w-full border-gray-300 rounded-md shadow-sm" required />
                            <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                            <label class="block mt-2 text-sm font-medium text-gray-700">Nama Event</label>
                            <input type="text" name="photo_activity_name"
                                class="block w-full border-gray-300 rounded-md shadow-sm" required />
                            <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Lokasi Event</label>
                            <input type="text" name="photo_activity_location"
                                class="block w-full border-gray-300 rounded-md shadow-sm" required />
                            <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Caption</label>
                            <textarea name="photo_activity_caption" class="block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                            <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Event</label>
                            <input type="date" name="photo_activity_date"
                                class="block w-full border-gray-300 rounded-md shadow-sm" required />
                            <p class="hidden mt-2 text-sm text-red-500 invalid-feedback"></p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-2" id="infoModalFooter">
                    <button type="button" id="infoCloseBtn"
                        class="px-4 py-2 text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                        Tutup
                    </button>
                    <button type="button" id="infoEditBtn"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div
        class="grid justify-center w-full grid-cols-1 gap-8 m-8 max-h-50 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 xl:grid-cols-5">

    </div>
</body>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
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
</script>
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

        // Untuk table_umum_photo_activities (tanpa tempat)
        $('#locationSelector').change(function() {
            let values = $(this).val().split(',');
            let cabangId = values[0];
            let kategori = values[1]; // asumsikan kategori dikirim juga

            $.ajax({
                url: `/search-table-umum-photo-activity/${cabangId}/${kategori}`,
                method: 'GET',
                success: function(response) {
                    $('#result').html(response.id ? 'ID: ' + response.id :
                        '<p>Data not found</p>');
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
            openPhotoActivity: false,
            showCalendar: false,
            selectedLocation: '{{ $detailTable->namaCabang }}',
            selectedLocationId: '{{ $detailTable->idCabang }}',
            selectedTabelUmumId: '{{ $detailTable->idTabelUmum }}',
            selectedPhotoActivityText: '{{ 'Foto' . ucwords(str_replace('_', ' ', $detailTable->namaTabelUmum)) }}',
            selectedPhotoActivityKategori: "{{ $detailTable->namaTabelUmum }}",
            photoActivityList: [],
            tableData: {},
            selectedDate: new URLSearchParams(window.location.search).get('date') || '{{ now()->toDateString() }}',

            async fetchData() {
                try {
                    const response = await fetch(`/get-data/{{ $detailTable->idTabelUmum }}`);
                    const result = await response.json();

                    // get first on object value
                    this.tableData = result.cabang;
                    // this.selectedTempat = this.selectedTempat || "Pilih Tempat";
                    // this.selectedTempatId = this.selectedTempatId || null;

                    // Fetch daftar tabel umum photo activity untuk dropdown
                    const resPhoto = await fetch('/api/photo-activity-list?cabang_id=' + this.selectedLocationId);
                    const list = await resPhoto.json();
                    this.photoActivityList = list.map(item => ({
                        id: item.id,
                        text: "Foto" + ' ' + item.kategori_text,
                        kategori: item.kategori
                    }));
                    // Set default selected text
                    const selected = this.photoActivityList.find(i => i.id == this.selectedTabelUmumId);
                    if (selected) this.selectedPhotoActivityText = selected.text;
                } catch (error) {
                    console.error("Error fetching data:", error);
                }
            },

            // Fungsi ini dipanggil setiap filter berubah
            onFilterChange() {
                // Update hidden input setiap kali filter berubah
                document.getElementById('selectedDateHidden').value = this.selectedDate;
                document.getElementById('selectedCabangId').value = this.selectedLocationId;
                // fetch tabel_umum_id baru tanpa reload halaman
                fetch(
                        `/search-table-umum-photo-activity/${this.selectedLocationId}/${this.selectedPhotoActivityKategori}`
                    )
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.id) {
                            this.selectedTabelUmumId = data.id;
                            document.getElementById('selectedTabelUmumId').value = data.id;

                            const url = new URL(window.location.href);
                            url.searchParams.set('id_photo_activity', data.id);
                            window.history.pushState({}, '', url);

                            // reload DataTables
                            window.dispatchEvent(new Event('datatable:reload'));
                        } else {
                            alert('Data not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching data');
                    });
            },

            // Dipanggil saat user memilih lokasi
            selectLocation(location) {
                this.selectedLocation = location;
                this.selectedLocationId = this.tableData[location]['id'];
                this.openLocation = false;
                this.onFilterChange();
            },

            selectPhotoActivity(item) {
                this.selectedTabelUmumId = item.id;
                this.selectedPhotoActivityText = item.text;
                this.selectedPhotoActivityKategori = item.kategori
                document.getElementById('selectedTabelUmumId').value = item.id;
                // Optionally update cabang id if needed
                // this.selectedLocationId = ...;
                // document.getElementById('selectedCabangId').value = ...;
                window.dispatchEvent(new Event('datatable:reload'));
                this.openPhotoActivity = false;
                this.onFilterChange();
            }
        };
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let uploadedFiles = []; // Untuk menyimpan file dan metadata
    let table = null;
    let infoMode = 'view'; // 'view' | 'edit'
    let currentPhotoEventId = null;
    let currentPhotoEventData = null;

    function closeFileModal() {
        $("#fileModal").addClass("hidden").removeClass("flex");
        // reset uploadedFiles input file-input and imageUpload
        $("#file-input").val('');
        $("#imageUpload").val('');
        uploadedFiles = []; // Reset array uploadedFiles
        $("#fileDetailsContainer").empty(); // Bersihkan kontainer detail
    }

    function openPreviewModal(imageUrl) {
        const previewImage = document.getElementById('previewImage');
        const previewModal = document.getElementById('previewModal');
        previewImage.src = imageUrl;
        previewModal.classList.remove('hidden');
    }

    function closePreviewModal() {
        const previewModal = document.getElementById('previewModal');
        previewModal.classList.add('hidden');
        document.getElementById('previewImage').src = '';
    }

    function validDate(e) {
        const dateStart = document.getElementById("date_start").value;
        const dateEnd = document.getElementById("date_end").value;

        if (new Date(dateStart) > new Date(dateEnd)) {
            Toast.fire({
                icon: "error",
                title: 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!',
                timer: 3000,
            });

            document.getElementById("date_start").value = document.getElementById("date_start").getAttribute(
                'prev-date');
            document.getElementById("date_end").value = document.getElementById("date_end").getAttribute('prev-date');
        } else if (new Date(dateEnd) < new Date(dateStart)) {
            Toast.fire({
                icon: "error",
                title: 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal!',
                timer: 3000,
            });

            document.getElementById("date_start").value = document.getElementById("date_start").getAttribute(
                'prev-date');
            document.getElementById("date_end").value = document.getElementById("date_end").getAttribute('prev-date');
        } else {
            document.getElementById("date_start").setAttribute('prev-date', dateStart);
            document.getElementById("date_end").setAttribute('prev-date', dateEnd);
        }
    }

    function filterByDate(e) {
        $(this).prop('disabled', true);
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        table.ajax.reload();
        $('#filter-button').prop('disabled', false);
        $('#filter-button').html('Filter');
    }

    function resetFilter() {
        document.getElementById("date_start").value = "{{ date('Y-m-d') }}";
        document.getElementById("date_end").value = "{{ date('Y-m-d') }}";
        table.ajax.reload();
    }

    $(document).ready(function() {
        const $dropArea = $("#drop-area");
        const $fileInput = $("#file-input");
        const $imageUpload = $("#imageUpload");
        const $childDropArea = $("#child-drop-area");
        const $uploadingMessage = $("#uploading-message");
        let isUploading = false;

        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const previewUrl = URL.createObjectURL(file);

                uploadedFiles.push({
                    file,
                    previewUrl,
                    photo_activity_file_name: file.name,
                    photo_activity_name: '',
                    photo_activity_location: '',
                    photo_activity_caption: '',
                    photo_activity_date: ''
                });
            }

            renderFileDetailModal(); // Langsung buka modal isi detail
        }

        function renderFileDetailModal() {
            const container = $("#fileDetailsContainer");
            container.empty();

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const localDate = `${year}-${month}-${day}`;
            uploadedFiles.forEach((file, index) => {
                const html = `
        <div class="p-4 space-y-2 border rounded-md bg-gray-50">
            <h3 class="mb-2 text-lg font-semibold text-blue-600 truncate cursor-pointer preview-link hover:underline"
    data-url="${file.previewUrl}">
    File ${index + 1}: ${file.photo_activity_file_name}
</h3>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama File Photo</label>
                <input type="text" name="event_file_name_${index}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="${file.photo_activity_file_name.split('.')[0]}"/>
                    <p class="hidden mt-2 mb-2 text-sm text-red-500 invalid-feedback"></p>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700">Nama Event</label>
                <input type="text" name="photo_activity_name_${index}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    <p class="hidden mt-2 mb-2 text-sm text-red-500 invalid-feedback"></p>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700">Lokasi Event</label>
                <input type="text" name="photo_activity_location_${index}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    <p class="hidden mt-2 mb-2 text-sm text-red-500 invalid-feedback"></p>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700">Caption</label>
                <textarea name="photo_activity_caption_${index}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                    <p class="hidden mt-2 mb-2 text-sm text-red-500 invalid-feedback"></p>
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700">Tanggal Event</label>
                <input type="date" name="photo_activity_date_${index}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="${localDate}"/>
                    <p class="hidden mt-2 mb-2 text-sm text-red-500 invalid-feedback"></p>
            </div>
        </div>
    `;
                container.append(html);
            });

            $(document).on('click', '.preview-link', function() {
                const url = $(this).data('url');
                openPreviewModal(url);
            });

            $("#fileModal").removeClass("hidden").addClass("flex"); // Tampilkan modal
        }

        function onSubmitModal(e) {
            e.preventDefault();
            // Ambil semua input dan update di uploadedFiles[]
            uploadedFiles.forEach((file, index) => {
                file.photo_activity_file_name = $(`input[name="event_file_name_${index}"]`).val();
                file.photo_activity_name = $(`input[name="photo_activity_name_${index}"]`).val();
                file.photo_activity_location = $(`input[name="photo_activity_location_${index}"]`)
                    .val();
                file.photo_activity_caption = $(`textarea[name="photo_activity_caption_${index}"]`)
                    .val();
                file.photo_activity_date = $(`input[name="photo_activity_date_${index}"]`).val();
            });

            // Lanjut ke upload
            uploadAllFiles();
        }

        // add event listener submit fileForm
        $("#fileForm").on("submit", onSubmitModal);

        function uploadAllFiles() {
            if (uploadedFiles.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada file',
                    text: 'Silakan upload file terlebih dahulu.',
                });
                return;
            }

            const formData = new FormData();

            uploadedFiles.forEach((fileData) => {
                formData.append('files[]', fileData.file);
                formData.append('photo_activity_file_name[]', fileData.photo_activity_file_name);
                formData.append('photo_activity_name[]', fileData.photo_activity_name);
                formData.append('photo_activity_location[]', fileData.photo_activity_location);
                formData.append('photo_activity_caption[]', fileData.photo_activity_caption);
                formData.append('photo_activity_date[]', fileData.photo_activity_date);
            });

            // Ambil tabel_umum_id dinamis dari hidden input (yang selalu diupdate oleh Alpine)
            formData.append('tabel_umum_id', $('#selectedTabelUmumId').val());
            // Kirim juga cabang_id ke backend
            formData.append('cabang_id', $('#selectedCabangId').val());
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route('photo-activity.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    Swal.fire({
                        title: 'Uploading...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // reload table
                        if (table) {
                            table.ajax.reload(null, false);
                            closeFileModal();
                        } else {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        // Bersihkan semua pesan error sebelumnya
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        Object.keys(errors).forEach((key) => {
                            errors[key].forEach((msg, i) => {
                                let inputName = key.replace(/\./g, '_');

                                let input = $(`[name="${inputName}"]`);
                                if (input.length === 0) {
                                    input = $(`[name="${inputName}"]`).eq(
                                        i
                                    ); // fallback: cari berdasarkan nama array biasa
                                }

                                // Tambahkan error feedback
                                input.addClass('is-invalid');
                                input.after(
                                    `<p class="mt-1 text-sm text-red-600 invalid-feedback">${msg}</p>`
                                );
                            });
                        });
                        Swal.close();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload gagal',
                            text: 'Terjadi kesalahan.',
                        });
                    }
                }
            });
        }


        $childDropArea.on("dragover", function(e) {
            e.preventDefault();
            $dropArea.removeClass("opacity-70");
        });

        $childDropArea.on("dragleave", function() {
            $dropArea.addClass("opacity-70");
        });

        $childDropArea.on("drop", function(e) {
            e.preventDefault();
            $dropArea.addClass("opacity-70");
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        $childDropArea.on("click", function(e) {
            if (e.target.id == 'child-drop-area') {
                $fileInput.click();
            }
        });

        $dropArea.on("click", function() {
            $fileInput.click();
        });

        $dropArea.on("dragover", function(e) {
            e.preventDefault();
            $dropArea.removeClass("opacity-70");
        });

        $dropArea.on("dragleave", function() {
            $dropArea.addClass("opacity-70");
        });

        $dropArea.on("drop", function(e) {
            e.preventDefault();
            $dropArea.addClass("opacity-70");
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        $fileInput.on("change", function(e) {
            const files = e.target.files;
            handleFiles(files);
        });

        $imageUpload.on("change", function(e) {
            const files = e.target.files;
            handleFiles(files);
        });

        table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('photo-activity.data') }}",
                data: function(d) {
                    // Ambil tabel_umum_id dan date dari hidden input dan Alpine
                    d.tabel_umum_id = $('#selectedTabelUmumId').val();
                    d.date = $('#selectedDateHidden').val();
                }
            },
            columns: [{
                data: 'photos',
                name: 'photos',
                orderable: false,
                searchable: true
            }],
            lengthMenu: [
                [2, 10, 20, 60],
                [2, 10, 20, 60]
            ],
            destroy: true,
            order: [
                [0, 'desc']
            ],
            drawCallback: function(settings) {
                $('.file-options-button').off('click').on('click', function() {
                    const menu = $(this).next('.file-options-menu');
                    $('.file-options-menu').not(menu).addClass(
                        'hidden opacity-0 scale-95');
                    menu.toggleClass('hidden opacity-0 scale-95');
                });

                document.addEventListener("click", (event) => {
                    document.querySelectorAll(".file-options-menu").forEach((menu) => {
                        if (!menu.previousElementSibling.contains(event
                                .target) && !menu.contains(event.target)) {
                            menu.classList.add("hidden", "opacity-0",
                                "scale-95");
                        }
                    });
                });

                $('.rename-button').off('click').on('click', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    const p = $(this).parent().parent().parent().find('p');
                    const currentValue = p.data('current-value');
                    p.replaceWith(
                        `<input type="text" data-id="${id}" value="${currentValue}" data-current-value="${currentValue}" class="rename-input" style="color: black;">`
                    );
                    $(`input[data-id="${id}"]`).focus();

                    $(`input[data-id="${id}"]`).off('keypress').on('keypress', function(
                        e) {
                        if (e.which === 13) { // 13 = Enter key
                            $(this).blur();
                        }
                    });
                });

                $('body').off('blur', '.rename-input').on('blur', '.rename-input', function() {
                    document.querySelectorAll(".file-options-menu").forEach(menu => {
                        menu.classList.add("hidden", "opacity-0", "scale-95");
                    });
                    const id = $(this).data('id');
                    const newValue = $(this).val();
                    const input = $(this);
                    const current = input.data('current-value');

                    // Jika tidak ada perubahan, langsung replace kembali dengan teks awal
                    if (newValue === current) {
                        input.replaceWith(
                            `<p data-id="${id}" class="text-black truncate rename-trigger" data-current-value="${current}">${current}</p>`
                        );
                        return;
                    }

                    // Lakukan AJAX request untuk rename
                    $.ajax({
                        url: `/photo-activity/${id}/rename`,
                        method: 'POST',
                        data: {
                            file_name: newValue,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (!response.error) {
                                Toast.fire({
                                    icon: "success",
                                    title: response.message,
                                    timer: 1500,
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: response.message,
                                    timer: 1500,
                                });
                            }
                        }
                    }).always(() => {
                        table.ajax.reload(null, false);
                    });
                });


                $('.delete-button').off('click').on('click', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    console.log(id);
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/photo-activity/${id}`,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (!response.error) {
                                        Toast.fire({
                                            icon: "success",
                                            title: response
                                                .message,
                                            timer: 1500,
                                        });
                                        table.ajax.reload(null,
                                            false);
                                    } else {
                                        let errorMessage = '';
                                        for (const [key,
                                                value
                                            ] of Object
                                            .entries(response
                                                .errors)) {
                                            errorMessage +=
                                                `${value}\n`;
                                        }
                                        Toast.fire({
                                            icon: "error",
                                            title: errorMessage,
                                            timer: 1500,
                                        });
                                    }
                                },
                                error: function(response) {
                                    response = response
                                        .responseJSON;
                                    let errorMessage = '';
                                    for (const [key,
                                            value
                                        ] of Object.entries(
                                            response.errors)) {
                                        errorMessage +=
                                            `${value}\n`;
                                    }
                                    Toast.fire({
                                        icon: "error",
                                        title: errorMessage,
                                        timer: 1500,
                                    });
                                }
                            });
                        }
                    });
                });

                // Info button
                $('.info-button').off('click').on('click', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    // Fetch detail via AJAX
                    $.ajax({
                        url: `/photo-activity/${id}`,
                        type: 'GET',
                        beforeSend: function() {
                            // disable button and loading
                            $('.info-button').prop('disabled', true);
                            $('.info-button').html(
                                '<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(res) {
                            openInfoModal(res.data);
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: "error",
                                title: "Gagal memuat data"
                            });
                        },
                        complete: function() {
                            $('.info-button').prop('disabled', false);
                            $('.info-button').html('Info');
                        }
                    });
                });
            }
        });

        // Reload DataTables jika filter berubah
        window.addEventListener('datatable:reload', function() {
            if (table) table.ajax.reload();
        });
    });

    const dropdownButton = document.getElementById("dropdownButton");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (dropdownButton) {
        dropdownButton.addEventListener("click", () => {
            dropdownMenu.classList.toggle("hidden");
            dropdownMenu.classList.toggle("opacity-0");
            dropdownMenu.classList.toggle("scale-95");
        });
    }

    document.querySelectorAll('.rename-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const id = button.dataset.id;
            const input = document.querySelector(`input[data-id="${id}"]`);
            input.removeAttribute('readonly');
            input.focus();
        });
    });

    // Hapus kode berikut jika ada di JS-mu (ini penyebab bug trigger rename!):

    // Info/Edit Modal Logic
    function attachView(data) {
        $('#info-edit-block [name="file"]').val('');
        // Set data-url on the h3 and update the filename
        $('#info-view-file')
            .text(data.photo_activity_file_name || data.file_name);
        $('#info-view-file')
            .parent('h3')
            .attr('data-url', data.file_url || data.file_path);

        // Remove previous click handler to avoid stacking
        $('#info-view-file').parent('h3').off('click');
        // Attach click handler directly to h3 (not delegated)
        $('#info-view-file').parent('h3').on('click', function() {
            const url = $(this).attr('data-url');
            if (url) openPreviewModal(url);
        });

        $('#info-view-event').text(data.photo_activity_name || '-');
        $('#info-view-location').text(data.photo_activity_location || '-');
        $('#info-view-caption').text(data.photo_activity_caption || '-');
        $('#info-view-date').text(data.photo_activity_date_text || '-');
        $('#info-view-block').show();
        $('#info-edit-block').hide();
        $("#infoModalFooter").html(`
            <button type="button" id="infoCloseBtn"
                class="px-4 py-2 text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                Tutup
            </button>
            <button type="button" id="infoEditBtn"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Edit
            </button>
        `);
    }

    function attachEdit(data) {
        $('#info-edit-file')
            .text(data.photo_activity_file_name || data.file_name);
        $('#info-edit-file')
            .parent('h3')
            .attr('data-url', data.file_url || data.file_path);

        $('#info-edit-file').parent('h3').off('click');
        $('#info-edit-file').parent('h3').on('click', function() {
            const url = $(this).attr('data-url');
            if (url) openPreviewModal(url);
        });

        $('[name="photo_activity_file_name"]').val(data.photo_activity_file_name || data.file_name || '');
        $('[name="photo_activity_name"]').val(data.photo_activity_name || '');
        $('[name="photo_activity_location"]').val(data.photo_activity_location || '');
        $('[name="photo_activity_caption"]').val(data.photo_activity_caption || '');
        $('[name="photo_activity_date"]').val(data.photo_activity_date || '');
        $('#info-view-block').hide();
        $('#info-edit-block').show();
        $("#infoModalFooter").html(`
            <button type="button" id="infoCloseBtn"
                class="px-4 py-2 text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                Tutup
            </button>
            <button type="button" id="infoCancelBtn"
                class="px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                Cancel
            </button>
            <button type="submit" id="infoUpdateBtn"
                class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                Update
            </button>
        `);
    }

    function openInfoModal(data) {
        infoMode = 'view';
        currentPhotoEventId = data.id_photo_activity;
        currentPhotoEventData = data;
        attachView(data);
        $("#infoModal").removeClass("hidden").addClass("flex");
    }

    // Open Info Modal
    $('body').on('click', '.info-button', function() {
        const id = $(this).data('id');

        // using ajax error and succcess and complete and before send
        $.ajax({
            url: `/photo-activity/${id}`,
            type: 'GET',
            beforeSend: function() {
                // disable button and loading
                $('.info-button').prop('disabled', true);
                $('.info-button').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(res) {
                openInfoModal(res.data);
            },
            error: function(xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Gagal memuat data"
                });
            },
            complete: function() {
                $('.info-button').prop('disabled', false);
                $('.info-button').html('Info');
            }
        });
    });

    // Preview file in modal (both view/edit)
    $('body').on('click', '#info-view-file, #info-edit-file', function() {
        const url = $(this).data('url');
        if (url) openPreviewModal(url);
    });

    // Edit mode
    $('body').on('click', '#infoEditBtn', function() {
        infoMode = 'edit';
        attachEdit(currentPhotoEventData);
    });

    // Cancel edit
    $('body').on('click', '#infoCancelBtn', function() {
        infoMode = 'view';
        attachView(currentPhotoEventData);
    });

    // Tutup modal
    $('#infoModalFooter').on('click', '#infoCloseBtn', function() {
        $("#infoModal").addClass("hidden").removeClass("flex");
        infoMode = 'view';
        currentPhotoEventId = null;
        currentPhotoEventData = null;
    });

    // Update
    $('#infoForm').on('submit', function(e) {
        if (infoMode !== 'edit') return false;
        // disabled button
        $('#infoUpdateBtn').prop('disabled', true);
        $('#infoUpdateBtn').html('<i class="fa fa-spinner fa-spin"></i> Updating data...');
        e.preventDefault();
        $('.invalid-feedback').addClass('hidden').text('');
        let formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        $.ajax({
            url: `/photo-activity/${currentPhotoEventId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                Toast.fire({
                    icon: "success",
                    title: res
                        .message,
                    timer: 1500,
                });
                if (table) {
                    table.ajax.reload(null, false);
                    closeFileModal();
                } else {
                    location.reload();
                }
                $("#infoModal").addClass("hidden").removeClass("flex");
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        let input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').removeClass('hidden').text(errors[
                            key][0]);
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Update gagal"
                    });
                }
            },
            complete: function() {
                $('#infoUpdateBtn').prop('disabled', false);
                $('#infoUpdateBtn').html('Update');
            }
        });
    });

    $('#infoModal').on('click', function(e) {
        if (e.target === this) {
            // do nothing
        }
    });
</script>

</html>
