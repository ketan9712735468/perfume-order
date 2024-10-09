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
    public function index()
    {
        $orderDetails = OrderDetail::all();
        return view('order_details.index', compact('orderDetails'));
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

