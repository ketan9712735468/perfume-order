<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Branch;
use App\Models\Vendor;
use App\Models\Type;
use App\Models\TrackingCompany;
use App\Models\StockControlStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        // Fetch related data for filters
        $branches = Branch::all();
        $vendors = Vendor::all();
        $types = Type::all();
        $trackingCompanies = TrackingCompany::all();
        $stockControlStatuses = StockControlStatus::all();

        // Fetch order details with relationships
        $orderDetails = OrderDetail::with(['branch', 'vendor', 'type', 'trackingCompany', 'stock_control_status'])->where('current', 0);

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

        // Date range filter for `email_date`
        if ($request->filled('email_date_start') && $request->filled('email_date_end')) {
            $orderDetails->whereBetween('email_date', [$request->email_date_start, $request->email_date_end]);
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

        return view('order_details.index', compact('orderDetails', 'branches', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }
    

    public function create()
    {
        $branches = Branch::where('enabled', true)->get();
        $vendors = Vendor::where('enabled', true)->get();
        $types = Type::where('enabled', true)->get();
        $trackingCompanies = TrackingCompany::where('enabled', true)->get();
        $stockControlStatuses = StockControlStatus::where('enabled', true)->get();

        return view('order_details.create', compact('branches', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'branch_id' => 'required',
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
        $orderDetail->employee = Auth::user()->name;
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

        $branches = Branch::where('enabled', true)->get();
        $vendors = Vendor::where('enabled', true)->get();
        $types = Type::where('enabled', true)->get();
        $trackingCompanies = TrackingCompany::where('enabled', true)->get();
        $stockControlStatuses = StockControlStatus::where('enabled', true)->get();

        return view('order_details.edit', compact('orderDetail', 'branches', 'vendors', 'types', 'trackingCompanies', 'stockControlStatuses'));
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        $validated = $request->validate([
            'branch_id' => 'required',
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
            $orderDetail->employee = Auth::user()->name;
            $orderDetail->save(); // Save the updated record

            // Create a new record with the same details but marked as current
            $newOrderDetail = new OrderDetail($validated);
            $newOrderDetail->current = 0; // New record is current
            $newOrderDetail->created_by = Auth::id();
            $newOrderDetail->updated_by = Auth::id();
            $newOrderDetail->employee = Auth::user()->name;
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

    public function download(Request $request)
    {
        // Fetch the filters from the request
        $filters = $request->all();

        // Apply filters to get the data
        $query = OrderDetail::with(['branch', 'vendor', 'type', 'trackingCompany', 'stock_control_status'])->where('current', 0);

        if (!empty($filters['search'])) {
            $query->where('sales_order', 'like', '%' . $filters['search'] . '%')
                ->orWhere('invoice_number', 'like', '%' . $filters['search'] . '%')
                ->orWhere('freight', 'like', '%' . $filters['search'] . '%')
                ->orWhere('units', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['branch_id'])) {
            $query->whereIn('branch_id', $filters['branch_id']);
        }

        if (!empty($filters['vendor_id'])) {
            $query->whereIn('vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['stock_control_status_id'])) {
            $query->whereIn('stock_control_status_id', $filters['stock_control_status_id']);
        }

        if (!empty($filters['email_date_start']) && !empty($filters['email_date_end'])) {
            $query->whereBetween('email_date', [$filters['email_date_start'], $filters['email_date_end']]);
        }

        $data = $query->get([
            'branch_id', 'employee', 'email_date', 'response_date', 'vendor_id', 
            'type_id', 'sales_order', 'invoice_number', 'freight', 'total_amount',
            'paid_date', 'paid_amount', 'variants', 'sb', 'rb', 'units', 
            'received', 'delivery_date', 'tracking_company_id', 'tracking_number', 
            'note', 'stock_control_status_id', 'order_number', 'link', 
        ]);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $headers = [
            'Branch', 'Employee', 'Email Date', 'Response Date', 'Vendor ID', 
            'Type ID', 'Sales Order', 'Invoice Number', 'Freight', 'Total Amount',
            'Paid Date', 'Paid Amount', 'Variants', 'SB', 'RB', 'Units', 
            'Received', 'Delivery Date', 'Tracking Company ID', 'Tracking Number', 
            'Note', 'Stock Control Status ID', 'Order Number', 'Link', 
        ];

        $sheet->fromArray($headers, null, 'A1');

        // Add data rows
        $rowIndex = 2; // Start from the second row
        foreach ($data as $row) {
            $sheet->fromArray([
                $row->branch ? $row->branch->name : null,
                $row->employee,
                $row->email_date ? \Carbon\Carbon::parse($row->email_date)->format('Y-m-d') : null,
                $row->response_date ? \Carbon\Carbon::parse($row->response_date)->format('Y-m-d') : null,
                $row->vendor ? $row->vendor->name : null,
                $row->type ? $row->type->name : null,
                $row->sales_order,
                $row->invoice_number,
                $row->freight,
                $row->total_amount,
                $row->paid_date ? \Carbon\Carbon::parse($row->paid_date)->format('Y-m-d') : null,
                $row->paid_amount,
                $row->variants,
                $row->sb,
                $row->rb,
                $row->units,
                $row->received,
                $row->delivery_date ? \Carbon\Carbon::parse($row->delivery_date)->format('Y-m-d') : null,
                $row->tracking_company ? $row->tracking_company->name : null,
                $row->tracking_number,
                $row->note,
                $row->stock_control_status ? $row->stock_control_status->name : null,
                $row->order_number,
                $row->link,
            ], null, 'A' . $rowIndex);

            $rowIndex++;
        }

        // Set file name
        $fileName = 'order_details.xlsx';

        // Set headers for the response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Cache-Control: max-age=0');

        // Save the file to output
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}

