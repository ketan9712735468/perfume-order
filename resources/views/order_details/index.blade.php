<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }}
            </h2>
            <a href="{{ route('order_details.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Create Order Detail</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-12">
                @if(session('success'))
                    <div class="fixed top-4 right-4 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded-md shadow-lg transition-opacity duration-300 ease-in-out opacity-100" role="alert">
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

                @if(session('error'))
                    <div class="fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-md shadow-lg transition-opacity duration-300 ease-in-out opacity-100" role="alert">
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

                <!-- Show validation errors -->
                @if($errors->any())
                    <div class="fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-md shadow-lg transition-opacity duration-300 ease-in-out opacity-100" role="alert">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3a1.5 1.5 0 1 1 3 0v12a1.5 1.5 0 1 1-3 0V3zm-1.5 12a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm">Please fix the following errors:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="ml-3 text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.parentElement.remove(), 300);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                            <th class="py-3 px-6 text-left">Branch</th>
                                            <th class="py-3 px-6 text-left">Emp. Name</th>
                                            <th class="py-3 px-6 text-left">Email Date</th>
                                            <th class="py-3 px-6 text-left">Response Date</th>
                                            <th class="py-3 px-6 text-left">Vendor Name</th>
                                            <th class="py-3 px-6 text-left">Type</th>
                                            <th class="py-3 px-6 text-left">Sales Order</th>
                                            <th class="py-3 px-6 text-left">Invoice Number</th>
                                            <th class="py-3 px-6 text-left">Freight</th>
                                            <th class="py-3 px-6 text-left">Total Amount</th>
                                            <th class="py-3 px-6 text-left">Paid Date</th>
                                            <th class="py-3 px-6 text-left">Paid Amount</th>
                                            <th class="py-3 px-6 text-left">Variants</th>
                                            <th class="py-3 px-6 text-left">SB</th>
                                            <th class="py-3 px-6 text-left">RB</th>
                                            <th class="py-3 px-6 text-left">Units</th>
                                            <th class="py-3 px-6 text-left">Received</th>
                                            <th class="py-3 px-6 text-left">Delivery Date</th>
                                            <th class="py-3 px-6 text-left">Tracking Company</th>
                                            <th class="py-3 px-6 text-left">Tracking Number</th>
                                            <th class="py-3 px-6 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 text-sm font-light">
                                        @foreach($orderDetails as $orderDetail)
                                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->branch->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->employee->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->email_date ? \Carbon\Carbon::parse($orderDetail->email_date)->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->response_date ? \Carbon\Carbon::parse($orderDetail->response_date)->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->vendor->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->type->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->sales_order }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->invoice_number }}</td>
                                                <td class="py-3 px-6 text-left">{{ number_format($orderDetail->freight, 2) }}</td>
                                                <td class="py-3 px-6 text-left">{{ number_format($orderDetail->total_amount, 2) }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->paid_date ? \Carbon\Carbon::parse($orderDetail->paid_date)->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ number_format($orderDetail->paid_amount, 2) }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->variants }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->sb }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->rb }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->units }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->received }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->delivery_date ? \Carbon\Carbon::parse($orderDetail->delivery_date)->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->trackingCompany->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-6 text-left">{{ $orderDetail->tracking_number }}</td>
                                                <td class="py-4 px-6 align-start text-center">
                                                    <div class="inline-flex items-center space-x-4">
                                                        <a href="{{ route('order_details.edit', $orderDetail) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>
                                                        </a>
                                                        <button type="button" onclick="openModal('{{ route('order_details.destroy', $orderDetail) }}')" class="text-gray-600 hover:text-gray-800">
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
            <p class="text-gray-700 mb-6">Are you sure you want to delete this order detail?</p>
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

    <script>
        function openModal(deleteUrl) {
            document.getElementById('deleteForm').action = deleteUrl;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    
        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
