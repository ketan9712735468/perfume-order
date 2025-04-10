<x-app-layout>
    <div class="flex-grow ml-64 mt-24 main_layout">
        <div class="max-w-7xl ml-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Create Vendor') }}
                        </h2>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <form action="{{ route('vendors.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="person_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Person Name') }}</label>
                                        <input type="text" id="person_name" name="person_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="person_email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Person Email') }}</label>
                                        <input type="email" id="person_email" name="person_email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Address') }}</label>
                                        <textarea id="address" name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>{{ old('note') }}</textarea>
                                    </div>
                                    <div class="mb-6">
                                        <label for="number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Number') }}</label>
                                        <input type="number" id="number" name="number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Slug') }}</label>
                                        <input type="text" id="slug" name="slug" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                                        <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"></textarea>
                                    </div>
                                    <!-- Enabled/Disabled Toggle -->
                                    <div class="mb-6">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="enabled" value="1" class="sr-only peer" checked>
                                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700">{{ __('Enabled') }}</span>
                                        </label>
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 mr-4 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Back') }}</a>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">{{ __('Create') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>