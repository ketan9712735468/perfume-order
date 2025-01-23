<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectInventory;
use App\Models\ReportData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\ResultFile;
use Illuminate\Support\Facades\Storage;

class ProjectInventoryController extends Controller
{

    protected function parseInventoryFile($file)
    {
        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
    
        // Array to hold parsed data
        $data = [];
    
        // Read headers from the first row and map them by column letter
        $header = [];
        foreach ($worksheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $columnLetter = $cell->getColumn();
                $header[$columnLetter] = strtolower(trim($cell->getValue()));
            }
        }
    
        // Expected columns and mappings
        $columnMappings = [
            'product' => 'name',
            'sku' => 'product_sku',
            'brand' => 'brand',
            'category' => 'category',
        ];
    
        // Determine column letters instead of indices
        $columnIndices = [];
        foreach ($columnMappings as $fileColumn => $dbField) {
            $columnLetter = array_search($fileColumn, $header);
            if ($columnLetter !== false) {
                $columnIndices[$dbField] = $columnLetter;
            }
        }
    
        Log::debug("Column Letters: ", $columnIndices);
    
        // Start reading data from the second row onward
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];
    
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
    
            foreach ($cellIterator as $cell) {
                $columnLetter = $cell->getColumn();
                $value = trim($cell->getValue());
    
                // Log each cell's value with its letter
                Log::debug("Cell Value at Column $columnLetter: ", ['value' => $value]);
    
                // Match cell values to the correct fields based on column letters
                if (isset($columnIndices['product_sku']) && $columnLetter === $columnIndices['product_sku']) {
                    $rowData['product_sku'] = $value;
                } elseif (isset($columnIndices['name']) && $columnLetter === $columnIndices['name']) {
                    $rowData['name'] = $value;
                } elseif (isset($columnIndices['brand']) && $columnLetter === $columnIndices['brand']) {
                    $rowData['brand'] = $value;
                } elseif (isset($columnIndices['category']) && $columnLetter === $columnIndices['category']) {
                    $rowData['category'] = $value;
                }
            }
    
            // Add row to data if SKU and name are present
            if (!empty($rowData['product_sku']) && !empty($rowData['name'])) {
                $data[] = $rowData;
            }
        }
    
        // Final log for parsed data
        Log::debug("Parsed Data Array: ", $data);
    
        return $data;
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);
        Log::debug("this is request ");
        if ($request->hasFile('file')) {
            $file = $request->file('file');


            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();

            $fileName = 'projects_' . time() . '_' . Str::random(10) . '.' . $extension;
            $file->storeAs(ProjectInventory::$FOLDER_PATH, $fileName);

            $project->inventories()->create([
                'file' => $fileName,
                'original_name' => $originalFileName,
            ]);

            // Parse the inventory file content
            $parsedData = $this->parseInventoryFile($file);
            Log::debug("message",["a"=>$parsedData]);

            // Save each parsed record to ReportData
            foreach ($parsedData as $data) {
                ReportData::create([
                    'product_sku' => $data['product_sku'],
                    'name' => $data['name'],
                    'brand' => $data['brand'],
                    'category' => $data['category'],
                    'price' => null, // price is only in vendor files
                    'file_name' => 'inventory',
                    'project_id' => $project->id,
                ]);
            }
        }
        
        return response()->json(['status' => 'Files uploaded successfully']);
    }

    public function preview($filename)
    {
        $filePath = storage_path('app/private/uploads/project_inventories/' . $filename);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
        } catch (\Exception $e) {
            // Handle the exception if the file cannot be read
            return response()->json(['error' => 'Unable to read the file.'], 500);
        }

        // Render the HTML content of the preview
        $htmlContent = view('files.preview', compact('data'))->render();

        return response()->json(['html' => $htmlContent]);
    }

    public function download($filename)
    {
        $projectinventory = ProjectInventory::find($filename);
        $path = storage_path('app/private/uploads/project_inventories/' . $projectinventory->file);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response()->download($path, $projectinventory->original_name, ['Content-Type' => $type]);
    }

    public function destroy(ProjectInventory $inventory)
    {
        $inventory->delete();
        return redirect()->back();
    }
}
