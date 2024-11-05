<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ReportData;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct file names and remove the .xlsx extension
        $fileNames = ReportData::where('file_name', '!=', 'inventory') // Exclude "inventory"
            ->distinct()
            ->pluck('file_name')
            ->map(function ($fileName) {
                return str_replace('.xlsx', '', $fileName); // Remove the .xlsx extension
            });

        // Build the select statement
        $selects = [
            'product_sku',
            \DB::raw("MAX(name) as name"),
            \DB::raw("MAX(brand) as brand"),
            \DB::raw("MAX(category) as category"),
            'project_id',
            \DB::raw("DATE(created_at) as created_date"),
        ];

        // Create the CASE statements for each file name
        foreach ($fileNames as $file) {
            $selects[] = \DB::raw("MAX(CASE WHEN file_name = '{$file}.xlsx' THEN price END) as `{$file}_price`");
        }
        
        // Start building the query
        $query = ReportData::select($selects)
            ->groupBy('product_sku', 'project_id', \DB::raw("DATE(created_at)"));

        // Apply filters if they exist
        if ($request->filled('product_sku')) {
            $query->where('product_sku', $request->input('product_sku'));
        }
        if ($request->filled('name')) {
            dd($request->input('product_sku'));
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Paginate the results
        $reportData = $query->paginate(100);

        return view('reports.index', compact('reportData', 'fileNames')); // Pass processed file names to the view
    }

}