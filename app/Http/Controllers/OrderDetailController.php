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
use Illuminate\Support\Facades\Auth;
class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        // Fetch related data for filters
        $branches = Branch::all();
        $employees = Employee::all();
        $vendors = Vendor::all();
        $types = Type::all();
        $trackingCompanies = TrackingCompany::all();
        $stockControlStatuses = StockControlStatus::all();

        // Fetch order details with relationships
        $orderDetails = OrderDetail::with(['branch', 'employee', 'vendor', 'type', 'trackingCompany', 'stock_control_status'])->where('current', 0);

        // Apply sorting if `sort_by` is present in the request
        if ($request->filled('sort_by') && $request->filled('order')) {
            $orderDetails->orderBy($request->input('sort_by'), $request->input('order'));
        } else {
            // Default sorting if no sorting parameters are provided
            $orderDetails->orderBy('created_at', 'desc');
        }

        // Apply filtering logic (same as before)
        if ($request->filled('branch_id')) {
            $orderDetails->whereIn('branch_id', $request->branch_id);
        }
        if ($request->filled('employee_id')) {
            $orderDetails->whereIn('employee_id', $request->employee_id);
        }
        if ($request->filled('vendor_id')) {
            $orderDetails->whereIn('vendor_id', $request->vendor_id);
        }
        if ($request->filled('type_id')) {
            $orderDetails->whereIn('type_id', $request->type_id);
        }
        if ($request->filled('tracking_company_id')) {
            $orderDetails->whereIn('tracking_company_id', $request->tracking_company_id);
        }
        if ($request->filled('stock_control_status_id')) {
            $orderDetails->whereIn('stock_control_status_id', $request->stock_control_status_id);
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
                    ->orWhere('tracking_number', 'like', "%{$searchTerm}%")
                    ->orWhere('note', 'like', "%{$searchTerm}%")
                    ->orWhere('order_number', 'like', "%{$searchTerm}%");
            });
        }

        // Paginate the results
        $orderDetails = $orderDetails->paginate(100);

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
            'freight' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'paid_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'variants' => 'nullable|string',
            'sb' => 'nullable|integer',
            'rb' => 'nullable|integer',
            'units' => 'nullable|integer',
            'received' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'tracking_company_id' => 'nullable',
            'tracking_number' => 'nullable|string',
            'note' => 'nullable|string',
            'stock_control_status_id' => 'nullable',
            'order_number' => 'nullable|string',
            'link' => 'nullable|string',
        ]);

        // Create a new OrderDetail instance and fill it with validated data
        $orderDetail = new OrderDetail($validated);
        // Set created_by and updated_by to the currently authenticated user
        $orderDetail->created_by = Auth::id();
        $orderDetail->save();

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
            'freight' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'paid_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric',
            'variants' => 'nullable|string',
            'sb' => 'nullable|integer',
            'rb' => 'nullable|integer',
            'units' => 'nullable|integer',
            'received' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'tracking_company_id' => 'nullable',
            'tracking_number' => 'nullable|string',
            'note' => 'nullable|string',
            'stock_control_status_id' => 'nullable',
            'order_number' => 'nullable|string',
            'link' => 'nullable|string',
        ]);
        try {

            $orderDetail->current = 1; // Set old record to not current
            $orderDetail->updated_by = Auth::id();
            $orderDetail->save(); // Save the updated record

            // Create a new record with the same details but marked as current
            $newOrderDetail = new OrderDetail($validated);
            $newOrderDetail->current = 0; // New record is current
            $newOrderDetail->created_by = Auth::id();
            $newOrderDetail->updated_by = Auth::id();
            $newOrderDetail->save();

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

    public function bulkDelete(Request $request)
    {
        $selectedOrders = $request->input('selected_orders', []);

        if (count($selectedOrders) > 0) {
            $updatedRows = OrderDetail::whereIn('id', $selectedOrders)->update(['current' => 1]);
            
            return redirect()->back()->with('success', 'Selected orders deleted successfully.');
        }

        return redirect()->back()->with('error', 'No orders selected for deletion.');
    }

}

