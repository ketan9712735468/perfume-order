<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }}
            </h2>
            <div class="space-x-4">
                <a href="{{ route('order_details.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    Create Order Detail
                </a>
                <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    Delete Selected
                </button>
            </div>
        </div>
    </x-slot>

    <div class="overflow-hidden mt-6">
        <div class="mx-auto px-4">
            <div class="bg-white shadow-xl rounded-lg">
                <div class="p-4">

                    <!-- Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="flex items-center justify-center space-x-4">
                            <input type="text" name="search" placeholder="Search..." class="border rounded-md p-2 w-1/4" value="{{ request('search') }}">

                            <select class="js-example-basic-multiple border rounded-md p-2 w-1/4" name="branch_id[]" multiple="multiple" id="branch-select">
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ is_array(request('branch_id')) && in_array($branch->id, request('branch_id')) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>

                            <select class="js-example-basic-multiple border rounded-md p-2 w-1/4" name="vendor_id[]" multiple="multiple" id="vendor-select">
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ is_array(request('vendor_id')) && in_array($vendor->id, request('vendor_id')) ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>

                            <select class="js-example-basic-multiple border rounded-md p-2 w-1/4" name="type_id[]" multiple="multiple" id="type-select">
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" {{ is_array(request('type_id')) && in_array($type->id, request('type_id')) ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>

                            <select class="js-example-basic-multiple border rounded-md p-2 w-1/4" name="stock_control_status_id[]" multiple="multiple" id="stock-control-status-select">
                                @foreach ($stockControlStatuses as $status)
                                    <option value="{{ $status->id }}" {{ is_array(request('stock_control_status_id')) && in_array($status->id, request('stock_control_status_id')) ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>

                            <!-- Date Range for Email Date -->
                            <input type="date" name="email_date_start" class="border rounded-md p-2" value="{{ request('email_date_start') }}" placeholder="Start Date">
                            <input type="date" name="email_date_end" class="border rounded-md p-2" value="{{ request('email_date_end') }}" placeholder="End Date">

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">{{ __('Filter') }}</button>
                        </div>
                    </form>

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
                    <form id="bulkDeleteForm" method="POST" action="{{ route('order_details.bulk_delete') }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                </th>
                                <th class="py-3 px-6 text-left">Sr No.</th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=branch_id&order={{ request('sort_by') == 'branch_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Branch
                                            @if(request('sort_by') == 'branch_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=employee_id&order={{ request('sort_by') == 'employee_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Emp. Name
                                            @if(request('sort_by') == 'employee_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=email_date&order={{ request('sort_by') == 'email_date' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Email Date
                                            @if(request('sort_by') == 'email_date') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=response_date&order={{ request('sort_by') == 'response_date' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Response Date
                                            @if(request('sort_by') == 'response_date') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=vendor_id&order={{ request('sort_by') == 'vendor_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Vendor Name
                                            @if(request('sort_by') == 'vendor_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=type_id&order={{ request('sort_by') == 'type_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Type
                                            @if(request('sort_by') == 'type_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=sales_order&order={{ request('sort_by') == 'sales_order' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Sales Order
                                            @if(request('sort_by') == 'sales_order') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=invoice_number&order={{ request('sort_by') == 'invoice_number' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Invoice Number
                                            @if(request('sort_by') == 'invoice_number') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=freight&order={{ request('sort_by') == 'freight' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Freight
                                            @if(request('sort_by') == 'freight') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=total_amount&order={{ request('sort_by') == 'total_amount' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Total Amount
                                            @if(request('sort_by') == 'total_amount') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=paid_date&order={{ request('sort_by') == 'paid_date' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Paid Date
                                            @if(request('sort_by') == 'paid_date') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=paid_amount&order={{ request('sort_by') == 'paid_amount' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Paid Amount
                                            @if(request('sort_by') == 'paid_amount') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=variants&order={{ request('sort_by') == 'variants' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Variants
                                            @if(request('sort_by') == 'variants') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=sb&order={{ request('sort_by') == 'sb' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            SB
                                            @if(request('sort_by') == 'sb') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=rb&order={{ request('sort_by') == 'rb' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            RB
                                            @if(request('sort_by') == 'rb') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=units&order={{ request('sort_by') == 'units' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Units
                                            @if(request('sort_by') == 'units') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=received&order={{ request('sort_by') == 'received' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Received
                                            @if(request('sort_by') == 'received') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=delivery_date&order={{ request('sort_by') == 'delivery_date' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Delivery Date
                                            @if(request('sort_by') == 'delivery_date') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=tracking_company_id&order={{ request('sort_by') == 'tracking_company_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Tracking Company
                                            @if(request('sort_by') == 'tracking_company_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=tracking_number&order={{ request('sort_by') == 'tracking_number' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Tracking Number
                                            @if(request('sort_by') == 'tracking_number') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        Note
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=stock_control_status_id&order={{ request('sort_by') == 'stock_control_status_id' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Stock Control Status
                                            @if(request('sort_by') == 'stock_control_status_id') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">
                                        <a href="?sort_by=order_number&order={{ request('sort_by') == 'order_number' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                            Order Number
                                            @if(request('sort_by') == 'order_number') &#8593; @endif
                                        </a>
                                    </th>
                                    <th class="py-3 px-6 text-left">Link</th>
                                    <th class="py-4 px-6 align-start text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                                @foreach($orderDetails as $orderDetail)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left">
                                            <input type="checkbox" name="selected_orders[]" value="{{ $orderDetail->id }}" class="select-row">
                                        </td>
                                        <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->branch->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->employee->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->email_date ? $orderDetail->email_date->format('m/d/Y') : '' }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->response_date ? $orderDetail->response_date->format('m/d/Y'): '' }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->vendor->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->type->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->sales_order }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->invoice_number }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->freight }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->total_amount }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->paid_date ? $orderDetail->paid_date->format('m/d/Y') : ''}}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->paid_amount }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->variants }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->sb }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->rb }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->units }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->received }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->delivery_date ? $orderDetail->delivery_date->format('m/d/Y') : '' }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->trackingCompany?->name ?? '' }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->tracking_number }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->note }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->stock_control_status->name ?? '' }}</td>
                                        <td class="py-3 px-6 text-left">{{ $orderDetail->order_number }}</td>
                                        <td class="py-3 px-6 text-left">
                                            @if($orderDetail->link)
                                                <a href="{{ $orderDetail->link }}" target="_blank">Click</a>
                                            @else
                                                {{ '' }}
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 align-start text-center">
                                            <div class="inline-flex items-center space-x-4">
                                            @if($orderDetail->trackingCompany && $orderDetail->trackingCompany->link && $orderDetail->tracking_number)
                                            <a href="{{ str_replace('{tracking_number}', $orderDetail->tracking_number, $orderDetail->trackingCompany->link) }}" 
                                                target="_blank" 
                                                class="text-gray-600 hover:text-gray-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>
                                            @endif
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
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Confirm Deletion</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this Order Details?</p>
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
    
    $(document).ready(function() {
        $('#vendor-select').select2({
            placeholder: 'Select Vendor',
            allowClear: true
        });
        $('#branch-select').select2({
            placeholder: 'Select Branch',
            allowClear: true
        });
        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
        $('#stock-control-status-select').select2({
            placeholder: 'Select Stock Control Status',
            allowClear: true
        });
    });

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('input[name="selected_orders[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }

    function confirmDelete() {
        const checkboxes = document.querySelectorAll('input[name="selected_orders[]"]:checked');
            document.getElementById('bulkDeleteForm').submit();
    }
</script>
</x-app-layout>
