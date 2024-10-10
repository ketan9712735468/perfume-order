<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Vendor;
use App\Models\Type;
use App\Models\TrackingCompany;
use App\Models\StockControlStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        // Fetching all foreign data for filters
        $branches = Branch::all();
        $employees = Employee::all();
        $vendors = Vendor::all();
        $types = Type::all();
        $trackingCompanies = TrackingCompany::all();
        $stockControlStatuses = StockControlStatus::all();
    
        // Fetching order details with filtering logic
        $orderDetails = OrderDetail::with(['branch', 'employee', 'vendor', 'type', 'trackingCompany', 'stock_control_status']);
    
        // Apply filters if any
        if ($request->filled('branch_id')) {
            $orderDetails->where('branch_id', $request->branch_id);
        }
        if ($request->filled('employee_id')) {
            $orderDetails->where('employee_id', $request->employee_id);
        }
        if ($request->filled('vendor_id')) {
            $orderDetails->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('type_id')) {
            $orderDetails->where('type_id', $request->type_id);
        }
        if ($request->filled('tracking_company_id')) {
            $orderDetails->where('tracking_company_id', $request->tracking_company_id);
        }
        if ($request->filled('stock_control_status_id')) {
            $orderDetails->where('stock_control_status_id', $request->stock_control_status_id);
        }
    
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $orderDetails->where(function ($query) use ($searchTerm) {
                $query->where('sales_order', 'like', "%{$searchTerm}%")
                      ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                      ->orWhere('freight', 'like', "%{$searchTerm}%")
                      ->orWhere('total_amount', 'like', "%{$searchTerm}%")
                      ->orWhere('variants', 'like', "%{$searchTerm}%")
                      ->orWhere('sb', 'like', "%{$searchTerm}%")
                      ->orWhere('rb', 'like', "%{$searchTerm}%")
                      ->orWhere('units', 'like', "%{$searchTerm}%")
                      ->orWhere('received', 'like', "%{$searchTerm}%")
                      ->orWhere('note', 'like', "%{$searchTerm}%")
                      ->orWhere('order_number', 'like', "%{$searchTerm}%");
            });
        }
    
        // Execute the query and get the results
        $orderDetails = $orderDetails->paginate(100); // Use paginate or get based on your needs
    
        return view('order_details.index', compact('orderDetails', 'branches', 'employees', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }
    

    public function create()
    {
        $branches = Branch::all();
        $employees = Employee::all();
        $vendors = Vendor::all();
        $types = Type::all();
        $trackingCompanies = TrackingCompany::all();
        $stockControlStatuses = StockControlStatus::all();

        return view('order_details.create', compact('branches', 'employees', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'branch_id' => 'required',
            'employee_id' => 'required',
            'email_date' => 'required|date',
            'response_date' => 'required|date',
            'vendor_id' => 'required',
            'type_id' => 'required',
            'sales_order' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'freight' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'variants' => 'nullable|string',
            'sb' => 'nullable|integer',
            'rb' => 'nullable|integer',
            'units' => 'nullable|integer',
            'received' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'tracking_company_id' => 'required',
            'tracking_number' => 'nullable|string',
            'note' => 'nullable|string',
            'stock_control_status_id' => 'required',
            'order_number' => 'nullable|string',
        ]);

        // Create a new OrderDetail instance and fill it with validated data
        OrderDetail::create($validated);

        // Redirect back with a success message
        return redirect()->route('order_details.index')->with('success', 'Order detail created successfully.');
    }


    public function show(OrderDetail $orderDetail)
    {
        return view('order_details.show', compact('orderDetail'));
    }

    public function edit(OrderDetail $orderDetail)
    {
        $orderDetail->email_date = \Carbon\Carbon::parse($orderDetail->email_date);
        $orderDetail->response_date = \Carbon\Carbon::parse($orderDetail->response_date);
        $orderDetail->paid_date = \Carbon\Carbon::parse($orderDetail->paid_date);
        $orderDetail->delivery_date = \Carbon\Carbon::parse($orderDetail->delivery_date);

        $branches = Branch::all();
        $employees = Employee::all();
        $vendors = Vendor::all();
        $types = Type::all();
        $trackingCompanies = TrackingCompany::all();
        $stockControlStatuses = StockControlStatus::all();

        return view('order_details.edit', compact('orderDetail', 'branches', 'employees', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        $validated = $request->validate([
            'branch_id' => 'required',
            'employee_id' => 'required',
            'email_date' => 'required|date',
            'response_date' => 'required|date',
            'vendor_id' => 'required',
            'type_id' => 'required',
            'sales_order' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'freight' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'variants' => 'nullable|string',
            'sb' => 'nullable|integer',
            'rb' => 'nullable|integer',
            'units' => 'nullable|integer',
            'received' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'tracking_company_id' => 'required',
            'tracking_number' => 'nullable|string',
            'note' => 'nullable|string',
            'stock_control_status_id' => 'required',
            'order_number' => 'nullable|string',
        ]);
        try {
            $orderDetail->update($validated);

            // Redirect with success message
            return redirect()->route('order_details.index')->with('success', 'Order detail updated successfully.');

        } catch (\Exception $e) {
            dd($e);
            // Redirect with error message
            return redirect()->route('order_details.index')->with('error', 'Failed to update order detail.');
        }
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        return redirect()->route('order_details.index')->with('success', 'Order Detail deleted successfully.');
    }
}

