<div class="bg-[#d9d9d9] flex flex-col max-h-52 h-52">
    <div class="flex justify-between gap-4 mx-2">
        <p class="text-black truncate" id="file-name-text" data-id="{{ $row->id }}" data-current-value="{{ $row->file_name}}">{{ $row->file_name}}</p>
        <div class="relative">
            <i style="font-size: 20px;" class="mt-1 text-black cursor-pointer fa-solid fa-ellipsis-vertical file-options-button"></i>
            <div class="absolute hidden w-40 mt-2 overflow-hidden transition-all duration-300 transform scale-95 bg-white rounded-lg shadow-lg opacity-0 file-options-menu">
                <a href="#" class="block px-4 py-2 text-black transition-all duration-300 hover:bg-gray-100 rename-button" data-id="{{ $row->id_photo_activity }}">Rename</a>
                <a href="#" class="block px-4 py-2 text-black transition-all duration-300 hover:bg-gray-100 delete-button" data-id="{{ $row->id_photo_activity }}">Delete</a>
                <a href="{{ $row->getImage() }}" class="block px-4 py-2 text-black transition-all duration-300 hover:bg-gray-100" download>Download</a>
                <a href="#" class="block px-4 py-2 text-black transition-all duration-300 hover:bg-gray-100 info-button" data-id="{{ $row->id_photo_activity }}">Info</a>
            </div>
        </div>
    </div>
    <hr class="w-full border-black">
    <div class="flex items-center justify-center w-full h-full overflow-hidden">
        <img class="object-cover w-full h-full"
             src="{{ $row->getImage() }}?t={{ \Illuminate\Support\Str::random(8) }}"
             alt="">
    </div>
</div>
