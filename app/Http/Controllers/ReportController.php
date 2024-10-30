<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ReportData;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
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
            'project_id',
            'name',
            'brand',
            'category',
            \DB::raw("DATE(created_at) as date"),
        ];

        // Create the CASE statements for each file name
        foreach ($fileNames as $file) {
            $selects[] = \DB::raw("MAX(CASE WHEN file_name = '{$file}.xlsx' THEN price END) as `{$file}_price`");
        }

        // Execute the main query with pagination (e.g., 10 records per page)
        $reportData = ReportData::select($selects)
            ->groupBy('product_sku', 'project_id', 'name', 'brand', 'category', \DB::raw("DATE(created_at)"))
            ->paginate(500);

        return view('reports.index', compact('reportData', 'fileNames')); // Pass processed file names to the view
    }
}