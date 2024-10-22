<x-app-layout>

    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #d1d5db !important;
            border-radius: .375rem !important;
            line-height: 1.5rem !important;
            height: 42px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            height: 100% !important;
            display: flex !important;
        align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        }
    </style>

<div class="flex-grow ml-64 mt-24 main_layout">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Create Order Details') }}
                        </h2>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <form action="{{ route('order_details.store') }}" method="POST">
                                    @csrf

                                    <!-- Horizontal Field Group -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Branch Dropdown -->
                                        <div>
                                            <label for="branch_id" class="block text-gray-700">Branch</label>
                                            <select id="branch_id" name="branch_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Employee Name Dropdown -->
                                        <div>
                                            <label for="employee_id" class="block text-gray-700">Employee Name</label>
                                            <select id="employee_id" name="employee_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                                <option value="">Select Employee</option>    
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Email Date -->
                                        <div>
                                            <label for="email_date" class="block text-gray-700">Email Date</label>
                                            <input type="date" id="email_date" name="email_date" value="{{ old('email_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('email_date')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Response Date -->
                                        <div>
                                            <label for="response_date" class="block text-gray-700">Response Date</label>
                                            <input type="date" id="response_date" name="response_date" value="{{ old('response_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('response_date')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Vendor Name Dropdown -->
                                        <div>
                                            <label for="vendor_id" class="block text-gray-700">Vendor Name</label>
                                            <select id="vendor_id" name="vendor_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                                <option value="">Select Vendor</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('vendor_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Type Dropdown -->
                                        <div>
                                            <label for="type_id" class="block text-gray-700">Type</label>
                                            <select id="type_id" name="type_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                                <option value="">Select Type</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Sales Order -->
                                        <div>
                                            <label for="sales_order" class="block text-gray-700">Sales Order</label>
                                            <input type="text" id="sales_order" name="sales_order" value="{{ old('sales_order') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                            @error('sales_order')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Invoice Number -->
                                        <div>
                                            <label for="invoice_number" class="block text-gray-700">Invoice Number</label>
                                            <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                            @error('invoice_number')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Freight (Currency) -->
                                        <div>
                                            <label for="freight" class="block text-gray-700">Freight</label>
                                            <input type="number" step="0.01" id="freight" name="freight" value="{{ old('freight') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" placeholder="$0.00">
                                            @error('freight')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Total Amount (Currency) -->
                                        <div>
                                            <label for="total_amount" class="block text-gray-700">Total Amount</label>
                                            <input type="number" step="0.01" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required placeholder="$0.00">
                                            @error('total_amount')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Paid Date -->
                                        <div>
                                            <label for="paid_date" class="block text-gray-700">Paid Date</label>
                                            <input type="date" id="paid_date" name="paid_date" value="{{ old('paid_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('paid_date')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Paid Amount (Currency) -->
                                        <div>
                                            <label for="paid_amount" class="block text-gray-700">Paid Amount</label>
                                            <input type="number" step="0.01" id="paid_amount" name="paid_amount" value="{{ old('paid_amount') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" placeholder="$0.00" required>
                                            @error('paid_amount')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Variants -->
                                        <div>
                                            <label for="variants" class="block text-gray-700">Variants</label>
                                            <input type="text" id="variants" name="variants" value="{{ old('variants') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('variants')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- SB -->
                                        <div>
                                            <label for="sb" class="block text-gray-700">SB</label>
                                            <input type="text" id="sb" name="sb" value="{{ old('sb') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('sb')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- RB -->
                                        <div>
                                            <label for="rb" class="block text-gray-700">RB</label>
                                            <input type="text" id="rb" name="rb" value="{{ old('rb') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('rb')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Units -->
                                        <div>
                                            <label for="units" class="block text-gray-700">Units</label>
                                            <input type="number" id="units" name="units" value="{{ old('units') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('units')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Received -->
                                        <div>
                                            <label for="received" class="block text-gray-700">Received</label>
                                            <input type="number" id="received" name="received" value="{{ old('received') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('received')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Delivery Date -->
                                        <div>
                                            <label for="delivery_date" class="block text-gray-700">Delivery Date</label>
                                            <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                                            @error('delivery_date')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Tracking Company Dropdown -->
                                        <div>
                                            <label for="tracking_company_id" class="block text-gray-700">Tracking Company</label>
                                            <select id="tracking_company_id" name="tracking_company_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                                <option value="">Select Tracking Company</option>
                                                @foreach($trackingCompanies as $company)
                                                    <option value="{{ $company->id }}" {{ old('tracking_company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('tracking_company_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Tracking Number -->
                                        <div>
                                            <label for="tracking_number" class="block text-gray-700">Tracking Number</label>
                                            <input type="text" id="tracking_number" name="tracking_number" value="{{ old('tracking_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                            @error('tracking_number')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Order Number -->
                                        <div>
                                            <label for="order_number" class="block text-gray-700">Order Number</label>
                                            <input type="text" id="order_number" name="order_number" value="{{ old('order_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                            @error('order_number')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                        <!-- Stock Control Status Dropdown -->
                                        <div>
                                            <label for="stock_control_status_id" class="block text-gray-700">Stock Control Status</label>
                                            <select id="stock_control_status_id" name="stock_control_status_id" class="select-dropdown w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                                <option value="">Select Status</option>
                                                @foreach($stockControlStatuses as $stock_control_status)
                                                    <option value="{{ $stock_control_status->id }}" {{ old('stock_control_status_id') == $stock_control_status->id ? 'selected' : '' }}>{{ $stock_control_status->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('stock_control_status_id')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <!-- Link -->
                                        <div>
                                            <label for="link" class="block text-gray-700">Link</label>
                                            <input type="text" id="link" name="link" value="{{ old('link') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                            @error('link')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Note -->
                                        <div>
                                            <label for="note" class="block text-gray-700">Note</label>
                                            <textarea id="note" name="note" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">{{ old('note') }}</textarea>
                                            @error('note')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
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

<script>
    $(document).ready(function() {
        // Initialize Select2 for all dropdowns with the class 'select-dropdown'
        $('.select-dropdown').select2({
            placeholder: "Select an option", // Default placeholder
            allowClear: true // Allows clearing the selection
        });
    });
</script>
</x-app-layout>
