<x-app-layout>
    <style>
        /* Loader styles */
        .loader {
            width: 64px;
            height: 48px;
            position: relative;
            animation: split 1s ease-in infinite alternate;
        }
        .loader::before , .loader::after {
            content: '';
            position: absolute;
            height: 48px;
            width: 48px;
            border-radius: 50%;
            left: 0;
            top: 0;
            transform: translateX(-10px);
            background: #991b1b;
            opacity: 0.75;
            backdrop-filter: blur(20px);
        }
        .loader::after {
            left: auto;
            right: 0;
            background: #000;
            transform: translateX(10px);
        }

        @keyframes split {
            0% , 25%{ width: 64px }
            100%{ width: 148px }
        }
    </style>


    <div class="flex-grow ml-64 mt-24 main_layout">
        <div class="max-w-7xl ml-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="container mx-auto px-4">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="space-y-2">
                                        <h1 class="text-2xl font-semibold text-gray-900">{{ $project->name }}</h1>
                                        <p class="text-gray-700 mb-6">{{ $project->description }}</p>
                                    </div>
                                    <div class="space-x-2">
                                        <a href="{{ route('projects.files.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Upload Files</a>
                                        <a href="#" onclick="openMergeModal({{ $project->id }})" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Merge Files</a>
                                    </div>
                                </div>

                                <div class="mb-4 flex items-center justify-between">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('projects.show', ['project' => $project->id, 'type' => 'files']) }}" 
                                        class="{{ request('type', 'files') === 'files' ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700 font-bold py-2 px-4">
                                            Files
                                        </a>
                                        <a href="{{ route('projects.show', ['project' => $project->id, 'type' => 'results']) }}" 
                                        class="{{ request('type') === 'results' ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700 font-bold py-2 px-4">
                                            Results
                                        </a>
                                    </div>
                                    @if(request('type', 'files') === 'files')
                                    <!-- Bulk Action Buttons -->
                                    <div class="flex space-x-4">
                                        <!-- Bulk Enable Button -->
                                        <form id="bulk-enable-form" method="POST" action="{{ route('bulk_action', ['type' => 'enable']) }}">
                                            @csrf
                                            <input type="hidden" name="file_ids" id="enable-file-ids">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Enable
                                            </button>
                                        </form>

                                        <!-- Bulk Disable Button -->
                                        <form id="bulk-disable-form" method="POST" action="{{ route('bulk_action', ['type' => 'disable']) }}">
                                            @csrf
                                            <input type="hidden" name="file_ids" id="disable-file-ids">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                Disable
                                            </button>
                                        </form>

                                        <!-- Bulk Delete Button -->
                                        <form id="bulk-delete-form" method="POST" action="{{ route('bulk_action', ['type' => 'delete']) }}">
                                            @csrf
                                            <input type="hidden" name="file_ids" id="delete-file-ids">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>

                                <!-- Display error messages -->
                                @if(session('error'))
                                <div class="fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-md shadow-lg z-50 transition-opacity duration-300 ease-in-out opacity-100" role="alert">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3a1.5 1.5 0 1 1 3 0v12a1.5 1.5 0 1 1-3 0V3zm-1.5 12a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm">{{ session('error') }}</p>
                                        </div>
                                        <button type="button" class="ml-3 text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.parentElement.remove(), 300);">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <!-- Display success messages -->
                                @if(session('success'))
                                <div class="fixed top-4 right-4 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded-md shadow-lg z-50 transition-opacity duration-300 ease-in-out opacity-100" role="alert">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 4.5l-11.25 11.25-4.5-4.5m-1.5-1.5l6-6L20.25 4.5z" />
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm">{{ session('success') }}</p>
                                        </div>
                                        <button type="button" class="ml-3 text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.parentElement.remove(), 300);">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endif


                                @if(request('type', 'files') === 'files')
                                    <div class="bg-white shadow-md rounded my-6">
                                        <table class="text-left w-full border-collapse">
                                            <thead>
                                                <tr>
                                                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">File</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Type</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Date</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Enabled</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700 text-sm font-light">
                                                {{-- For Inventory file only --}}
                                                @foreach($project->inventories as $inventory)
                                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                    <td class="py-4 px-6">
                                                    </td>
                                                        <td class="py-4 px-6">
                                                            <span>{{ $inventory->original_name }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <span>Excel</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <span>{{ $inventory->created_at }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">Inventory
                                                        </td>
                                                        <td class="py-4 px-6 align-center text-center">
                                                            <div class="inline-flex items-center space-x-4">
                                                                <a href="#" onclick="openExcelModal('{{ route('inventory.preview', ['filename' => $inventory->file]) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                    </svg>
                                                                </a>
                                                                <a href="{{ route('inventory_download', ['filename' => $inventory->id]) }}" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                                    </svg>
                                                                </a>
                                                                <button type="button" onclick="openModal('{{ route('inventory.destroy', $inventory) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>                    
                                                    </tr>
                                                @endforeach

                                                {{-- For Project File only --}}
                                                @foreach($files as $file)
                                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                    <td class="py-4 px-6">
                                                        <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox">
                                                    </td>
                                                        <td class="py-4 px-6">
                                                            <span>{{ $file->original_name }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <span>Excel</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <span>{{ $file->created_at }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <form id="toggle-enabled-form-{{ $file->id }}" action="/perfume-service/files/toggle-enabled" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="file_id" value="{{ $file->id }}">
                                                                <input type="hidden" name="enabled" value="0">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" name="enabled" value="{{ $file->enabled ? '0' : '1' }}" class="sr-only peer" @if($file->enabled) checked @endif onchange="this.form.submit()">
                                                                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                                </label>
                                                            </form>
                                                        </td>
                                                        <td class="py-4 px-6 align-center text-center">
                                                            <div class="inline-flex items-center space-x-4">
                                                                <a href="#" onclick="openExcelModal('{{ route('excel.preview', ['filename' => $file->file]) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                    </svg>
                                                                </a>
                                                                <a href="{{ route('download', ['filename' => $file->id]) }}" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                                    </svg>
                                                                </a>
                                                                <button type="button" onclick="openModal('{{ route('files.destroy', $file) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>                    
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif(request('type') === 'results')
                                    <div class="bg-white shadow-md rounded my-6">
                                        <table class="text-left w-full border-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Result File</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Date</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200">Time</th>
                                                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-700 border-b border-gray-200 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700 text-sm font-light">
                                                @foreach($project->resultFiles->sortByDesc('created_at') as $resultFile)
                                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                        <td class="py-4 px-6">
                                                            <span>{{ $resultFile->original_name }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                            <span>{{ $resultFile->created_at->format('Y-m-d') }}</span>
                                                        </td>
                                                        <td class="py-4 px-6">
                                                        <span>{{ $resultFile->created_at->format('H:i') }}</span>
                                                        </td>
                                                        <td class="py-4 px-6 align-center text-center">
                                                            <div class="inline-flex items-center space-x-4">
                                                                <a href="#" onclick="openExcelModal('{{ route('result.preview', ['filename' => $resultFile->file]) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                    </svg>
                                                                </a>
                                                                <a href="{{ route('result_download', ['filename' => $resultFile->id]) }}" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                                    </svg>
                                                                </a>
                                                                <button type="button" onclick="openModal('{{ route('resultFiles.destroy', $resultFile) }}')" class="text-gray-600 hover:text-gray-800">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Confirm Deletion</h2>
            <p class="text-gray-700 mb-6">Are you sure you want to delete this File?</p>
            <div class="flex justify-end">
                <button onclick="closeModal()" class="inline-flex items-center px-4 py-2 mr-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Delete</button>
                </form>
            </div>
        </div>
    </div>

<!-- Excel Preview Modal -->
<div id="excelPreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-7xl mx-auto w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Excel Preview</h2>
            <button onclick="closeExcelModal()" class="text-gray-900 hover:text-gray-700 rounded-full bg-gray-200 p-2 focus:outline-none hover:bg-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="excelPreviewContent" class="w-full h-[600px] overflow-auto">
            <!-- Excel content will be injected here -->
        </div>
    </div>
</div>

<div id="mergeFilesModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col mt-6">
        <div class="px-4 py-5 sm:p-6 flex flex-col flex-grow overflow-y-auto">
            <h2 class="text-lg leading-6 font-medium text-gray-900 mb-4">Merge Files</h2>
             
            <form id="mergeFilesForm" method="POST" action="{{ route('projects.files.syncAll', $project) }}" onsubmit="showLoader()">
                @csrf

                <div id="filesSelectionContainer" class="mb-6" >
                    <div id="filesSelection" class="space-y-4">
                        <!-- These will be dynamically updated based on the API response -->
                    </div>
                </div>
                <div id="append_loader"></div>
                <div class="flex justify-end space-x-4 mt-4">
                    <button type="button" onclick="closeMergeModal()" class="inline-flex items-center px-4 py-2 mr-4 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Merge
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="loader"></div>
</div>

<script>
    function openModal(deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function openExcelModal(previewUrl) {
        document.getElementById('loadingIndicator').classList.remove('hidden');
        fetch(previewUrl)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingIndicator').classList.add('hidden');
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById('excelPreviewContent').innerHTML = data.html;
                    document.getElementById('excelPreviewModal').classList.remove('hidden');
                }
            })
            .catch(error => {
                document.getElementById('loadingIndicator').classList.add('hidden');
                console.error('Error fetching preview:', error);
                alert('An error occurred while fetching the file preview.');
            });
    }

    function closeExcelModal() {
        document.getElementById('excelPreviewContent').innerHTML = ''; // Clear the content
        document.getElementById('excelPreviewModal').classList.add('hidden');
    }

    function appendLoader(doc){
        doc.innerHTML += `<div class="flex items-center justify-center"><div class="loader"></div></div>`
    }

    function removeLoader(doc){
       const loader =  doc.querySelector(".loader")
       loader.remove()
    }

    function appendFileName(doc){
        const label = document.createElement('label');
        label.setAttribute('for', 'mergeFileName');
        label.classList.add('block', 'text-gray-700', 'mb-2');
        label.textContent = 'File Name';

        // Create input element
        const input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'mergeFileName');
        input.setAttribute('name', 'mergeFileName');
        input.setAttribute('required', true);
        input.classList.add('w-full', 'px-4', 'py-2', 'bg-gray-50', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');

        doc.appendChild(label)
        doc.appendChild(input)
    }

    function openMergeModal(projectId) {
        document.getElementById('mergeFilesModal').classList.remove('hidden');
        const mergeFileModal = document.getElementById('mergeFilesForm');
        const append_loader = document.getElementById('append_loader');
        appendLoader(append_loader);

        // Fetch inventory status from the API
        fetch('/perfume-service/check-inventory-file/' + projectId)
            .then(response => response.json())
            .then(data => {
                removeLoader(append_loader);

                // Set form action based on inventoryAvailable
                if (data.inventoryAvailable) {
                    const filesSelection = document.getElementById('filesSelection');
                    filesSelection.innerHTML = '';
                    mergeFileModal.setAttribute('action', '{{ route("projects.files.syncAll", $project) }}');
                    appendFileName(filesSelection);
                } else {
                    mergeFileModal.setAttribute('action', '{{ route("projects.files.manualSync", $project) }}');
                    updateFilesSelection(data.fileDetails);

                }
            })
            .catch(error => console.error('Error fetching inventory status:', error));
        }

    function updateFilesSelection(fileDetails) {
        const filesSelection = document.getElementById('filesSelection');
        filesSelection.innerHTML = ''; // Clear previous selection
        appendFileName(filesSelection);

        fileDetails.forEach(fileDetail => {
            const fileElement = document.createElement('div');
            fileElement.classList.add('mb-6');

            // Hidden field for file ID
            const fileIdInput = document.createElement('input');
            fileIdInput.type = 'hidden';
            fileIdInput.name = 'fileIds[]';
            fileIdInput.value = fileDetail.id;
            fileElement.appendChild(fileIdInput);

            // Label for file name
            const label = document.createElement('label');
            label.classList.add('block', 'text-gray-700', 'mb-2');
            label.textContent = fileDetail.original_name;
            fileElement.appendChild(label);

            // Dropdown for common column selection
            const commonColumnLabel = document.createElement('label');
            commonColumnLabel.classList.add('block', 'text-gray-700', 'mb-2');
            commonColumnLabel.textContent = `Select Common Column for ${fileDetail.original_name}`;
            fileElement.appendChild(commonColumnLabel);

            const commonColumnSelect = document.createElement('select');
            commonColumnSelect.name = `commonColumn[${fileDetail.id}]`;
            commonColumnSelect.classList.add('form-select', 'w-full', 'px-4', 'py-2', 'bg-gray-50', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
            commonColumnSelect.innerHTML = '<option value="">-- Select Common Column --</option>';
            
            Object.values(fileDetail.columns).forEach(column => {
                const option = document.createElement('option');
                option.value = column;
                option.textContent = column;
                if (column.toLowerCase() === 'upc') {
                    option.selected = true; // Pre-select UPC if found
                }
                commonColumnSelect.appendChild(option);
            });
            fileElement.appendChild(commonColumnSelect);

            // Dropdown for file columns
            const fileColumnsLabel = document.createElement('label');
            fileColumnsLabel.classList.add('block', 'text-gray-700', 'mb-2');
            fileColumnsLabel.textContent = `Select Columns for ${fileDetail.original_name}`;
            fileElement.appendChild(fileColumnsLabel);

            const fileColumnsSelect = document.createElement('select');
            fileColumnsSelect.name = `columns[${fileDetail.id}][]`;
            fileColumnsSelect.multiple = true;
            fileColumnsSelect.classList.add('form-select', 'w-full', 'px-4', 'py-2', 'bg-gray-50', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
            
            Object.values(fileDetail.columns).forEach(column => {
                const option = document.createElement('option');
                option.value = column;
                option.textContent = column;
                if (column.toLowerCase() === 'price') {
                    option.selected = true; // Pre-select Price if found
                }
                fileColumnsSelect.appendChild(option);
            });
            fileElement.appendChild(fileColumnsSelect);

            filesSelection.appendChild(fileElement);
        });
    }

    function closeMergeModal() {
        document.getElementById('mergeFilesModal').classList.add('hidden');
        const append_loader = document.getElementById('append_loader');
        const filesSelection = document.getElementById('filesSelection');
        filesSelection.innerHTML = '';
        removeLoader(append_loader);
    }

    function showLoader() {
        const append_loader = document.getElementById('append_loader');
        const filesSelection = document.getElementById('filesSelection');
        appendLoader(append_loader);
    }

    document.getElementById('select-all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    });

    // Add event listener for Enable button
    document.getElementById('bulk-enable-form').addEventListener('submit', function(event) {
        event.preventDefault();
        submitFormWithSelectedFiles('enable-file-ids', this);
    });

    // Add event listener for Enable button
    document.getElementById('bulk-disable-form').addEventListener('submit', function(event) {
        event.preventDefault();
        submitFormWithSelectedFiles('disable-file-ids', this);
    });

    // Add event listener for Delete button
    document.getElementById('bulk-delete-form').addEventListener('submit', function(event) {
        event.preventDefault();
        submitFormWithSelectedFiles('delete-file-ids', this);
    });

    function submitFormWithSelectedFiles(inputId, form) {
    const checkboxes = document.querySelectorAll('.file-checkbox:checked');
    if (checkboxes.length === 0) {
        showCustomAlert('Please select at least one file.');
        return;
    }
    
    // If there are selected files, continue the form submission
    const selectedFileIds = Array.from(checkboxes).map(checkbox => checkbox.value);
    document.getElementById(inputId).value = selectedFileIds.join(',');
    form.submit();
}

    function showCustomAlert(message) {
        // Create the alert container
        const alertBox = document.createElement('div');
        alertBox.className = "fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-md shadow-lg z-50 transition-opacity duration-300 ease-in-out opacity-100";
        alertBox.setAttribute('role', 'alert');

        // Alert content
        alertBox.innerHTML = `
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3a1.5 1.5 0 1 1 3 0v12a1.5 1.5 0 1 1-3 0V3zm-1.5 12a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm">${message}</p>
                </div>
                <button type="button" class="ml-3 text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.parentElement.remove(), 300);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;

        // Append the alert box to the body
        document.body.appendChild(alertBox);

        // Automatically remove the alert after 5 seconds
        setTimeout(() => {
            alertBox.style.opacity = '0';
            setTimeout(() => alertBox.remove(), 300);
        }, 5000);
    }
</script>
</x-app-layout>