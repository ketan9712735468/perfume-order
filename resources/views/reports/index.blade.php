<x-app-layout>

    <div class="flex-grow ml-64 mt-24 main_layout">
        <div class="max-w-7xl ml-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex justify-between">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Report') }}
                            </h2>
                            <form method="GET" action="{{ route('reports.index') }}" class="flex space-x-2">
                                <input type="text" name="product_sku" value="{{ request('product_sku') }}" placeholder="Filter by SKU" class="border border-gray-300 rounded px-2 py-1">
                                <input type="text" name="name" value="{{ request('name') }}" placeholder="Filter by Name" class="border border-gray-300 rounded px-2 py-1">
                                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filter</button>
                            </form>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="bg-white shadow-md rounded-lg p-6">
                                <!-- Horizontally scrollable table container -->
                                <div class="overflow-x-auto"> <!-- Enable horizontal scrolling only -->
                                    <table class="w-full table-auto">
                                        <thead>
                                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                                <th class="py-3 px-6 text-left">SKU</th>
                                                <th class="py-3 px-6 text-left">Project</th>
                                                <th class="py-3 px-6 text-left">Name</th>
                                                <th class="py-3 px-6 text-left">Brand</th>
                                                <th class="py-3 px-6 text-left">Category</th>
                                                <th class="py-3 px-6 text-left">Date</th>
                                                @foreach ($fileNames as $fileName)
                                                    <th class="py-3 px-6 text-left">{{ $fileName }}_Price</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700 text-sm font-light">
                                            @foreach($reportData as $report)
                                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->product_sku }}</span>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->project_id }}</span>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->name }}</span>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->brand }}</span>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->category }}</span>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">
                                                        <span class="font-medium">{{ $report->created_date }}</span>
                                                    </td>
                                                    @foreach ($fileNames as $fileName)
                                                        <td class="py-3 px-6 text-left">{{ $report->{$fileName . '_price'} ?? '' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination links -->
                                <div class="mt-4">
                                    {{ $reportData->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
