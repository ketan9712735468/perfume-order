<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('order_details.update', $orderDetail) }}">
                    @csrf
                    @method('PUT')
                    <div class="p-6">

                        <!-- Branch -->
                        <div class="mb-4">
                            <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch</label>
                            <select id="branch_id" name="branch_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branch->id == $orderDetail->branch_id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Employee Name -->
                        <div class="mb-4">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee Name</label>
                            <select id="employee_id" name="employee_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $employee->id == $orderDetail->employee_id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Date -->
                        <div class="mb-4">
                            <label for="email_date" class="block text-sm font-medium text-gray-700">Email Date</label>
                            <input type="date" id="email_date" name="email_date" value="{{ $orderDetail->email_date->format('Y-m-d') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Response Date -->
                        <div class="mb-4">
                            <label for="response_date" class="block text-sm font-medium text-gray-700">Response Date</label>
                            <input type="date" id="response_date" name="response_date" value="{{ $orderDetail->response_date->format('Y-m-d') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('response_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Vendor Name -->
                        <div class="mb-4">
                            <label for="vendor_id" class="block text-sm font-medium text-gray-700">Vendor Name</label>
                            <select id="vendor_id" name="vendor_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ $vendor->id == $orderDetail->vendor_id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label for="type_id" class="block text-sm font-medium text-gray-700">Type</label>
                            <select id="type_id" name="type_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ $type->id == $orderDetail->type_id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Sales Order -->
                        <div class="mb-4">
                            <label for="sales_order" class="block text-sm font-medium text-gray-700">Sales Order</label>
                            <input type="text" id="sales_order" name="sales_order" value="{{ $orderDetail->sales_order }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('sales_order')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Invoice Number -->
                        <div class="mb-4">
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                            <input type="text" id="invoice_number" name="invoice_number" value="{{ $orderDetail->invoice_number }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('invoice_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Freight -->
                        <div class="mb-4">
                            <label for="freight" class="block text-sm font-medium text-gray-700">Freight</label>
                            <input type="number" id="freight" name="freight" value="{{ $orderDetail->freight }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('freight')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Amount -->
                        <div class="mb-4">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                            <input type="number" id="total_amount" name="total_amount" value="{{ $orderDetail->total_amount }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('total_amount')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Paid Date -->
                        <div class="mb-4">
                            <label for="paid_date" class="block text-sm font-medium text-gray-700">Paid Date</label>
                            <input type="date" id="paid_date" name="paid_date" value="{{ $orderDetail->paid_date->format('Y-m-d') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('paid_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Paid Amount -->
                        <div class="mb-4">
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700">Paid Amount</label>
                            <input type="number" id="paid_amount" name="paid_amount" value="{{ $orderDetail->paid_amount }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('paid_amount')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Variants -->
                        <div class="mb-4">
                            <label for="variants" class="block text-sm font-medium text-gray-700">Variants</label>
                            <input type="text" id="variants" name="variants" value="{{ $orderDetail->variants }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('variants')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- SB -->
                        <div class="mb-4">
                            <label for="sb" class="block text-sm font-medium text-gray-700">SB</label>
                            <input type="text" id="sb" name="sb" value="{{ $orderDetail->sb }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('sb')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- RB -->
                        <div class="mb-4">
                            <label for="rb" class="block text-sm font-medium text-gray-700">RB</label>
                            <input type="text" id="rb" name="rb" value="{{ $orderDetail->rb }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('rb')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Units -->
                        <div class="mb-4">
                            <label for="units" class="block text-sm font-medium text-gray-700">Units</label>
                            <input type="number" id="units" name="units" value="{{ $orderDetail->units }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('units')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Received -->
                        <div class="mb-4">
                            <label for="received" class="block text-sm font-medium text-gray-700">Received</label>
                            <input type="text" id="received" name="received" value="{{ $orderDetail->received }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('received')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Delivery Date -->
                        <div class="mb-4">
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                            <input type="date" id="delivery_date" name="delivery_date" value="{{ $orderDetail->delivery_date->format('Y-m-d') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('delivery_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tracking Company -->
                        <div class="mb-4">
                            <label for="tracking_company_id" class="block text-sm font-medium text-gray-700">Tracking Company</label>
                            <select id="tracking_company_id" name="tracking_company_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($trackingCompanies as $company)
                                    <option value="{{ $company->id }}" {{ $company->id == $orderDetail->tracking_company_id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tracking_company_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tracking Number -->
                        <div class="mb-4">
                            <label for="tracking_number" class="block text-sm font-medium text-gray-700">Tracking Number</label>
                            <input type="text" id="tracking_number" name="tracking_number" value="{{ $orderDetail->tracking_number }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('tracking_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div class="mb-4">
                            <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                            <textarea id="note" name="note" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $orderDetail->note }}</textarea>
                            @error('note')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stock Control Status -->
                        <div class="mb-4">
                            <label for="stock_control_status_id" class="block text-sm font-medium text-gray-700">Stock Control Status</label>
                            <select id="stock_control_status_id" name="stock_control_status_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($stockControlStatuses as $status)
                                    <option value="{{ $status->id }}" {{ $status->id == $orderDetail->stock_control_status_id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stock_control_status_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Order Number -->
                        <div class="mb-4">
                            <label for="order_number" class="block text-sm font-medium text-gray-700">Order Number</label>
                            <input type="text" id="order_number" name="order_number" value="{{ $orderDetail->order_number }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('order_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 mr-4 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Back') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
