<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\ResultFile;
use App\Models\ReportData;
use Illuminate\Support\Facades\Storage;
use Aws\Lambda\LambdaClient;


class ProjectFileController extends Controller
{
    protected function parseVendorFile($file)
    {
        ini_set('memory_limit', '1G');
        // Load the spreadsheet in a memory-efficient way
        $reader = IOFactory::createReaderForFile($file->getPathname());
        $reader->setReadDataOnly(true); // Read only the data, no formatting
        $spreadsheet = $reader->load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();

        // Array to hold parsed data
        $data = [];

        // Read headers from the first row and map them by column letter
        $header = [];
        foreach ($worksheet->getRowIterator(1, 1) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);  // Only iterate over existing cells
            foreach ($cellIterator as $cell) {
                $columnLetter = $cell->getColumn();
                $header[$columnLetter] = strtolower(trim($cell->getValue()));
            }
        }

        // Define aliases for dynamic mapping
        $nameAliases = ['name', 'description', 'product name'];
        $skuAliases = ['upc', 'sku'];
        $priceAliases = ['price'];
        $brandAliases = ['brand'];
        $categoryAliases = ['category'];

        // Expected columns and mappings based on aliases
        $columnMappings = [
            'product_sku' => null,
            'name' => null,
            'price' => null,
            'brand' => null,
            'category' => null,
        ];

        // Map each alias to the correct column based on header row
        foreach ($header as $columnLetter => $headerValue) {
            if (in_array($headerValue, $skuAliases)) {
                $columnMappings['product_sku'] = $columnLetter;
            } elseif (in_array($headerValue, $nameAliases)) {
                $columnMappings['name'] = $columnLetter;
            } elseif (in_array($headerValue, $priceAliases)) {
                $columnMappings['price'] = $columnLetter;
            } elseif (in_array($headerValue, $brandAliases)) {
                $columnMappings['brand'] = $columnLetter;
            } elseif (in_array($headerValue, $categoryAliases)) {
                $columnMappings['category'] = $columnLetter;
            }
        }

        Log::debug("Mapped Columns: ", $columnMappings);

        // Start reading data from the second row onward
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];

            foreach ($columnMappings as $dbField => $columnLetter) {
                if ($columnLetter) {
                    $cell = $worksheet->getCell($columnLetter . $row->getRowIndex());
                    $value = trim($cell->getValue()); // Get the cell value
            
                    // Handle numeric fields explicitly
                    if ($dbField === 'price') {
                        // Normalize the price value
                        $value = str_replace([',', '$'], '', $value);
                        $rowData[$dbField] = is_numeric($value) ? round((float)$value, 2) : null;
                    } else {
                        $rowData[$dbField] = $value; // Handle other fields
                    }
                }
            }

            // Log and add row if key fields are present
            if (!empty($rowData['product_sku']) && !empty($rowData['name'])) {
                $data[] = $rowData;
            }
        }

        // Final log of all parsed data
        Log::debug("Parsed Data Array: ", $data);

        return $data;
    }

    public function index(Project $project)
    {
        $files = $project->files;
        return view('files.index', compact('project', 'files'));
    }

    public function create(Project $project)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the current team of the user
        $team_id = $user->currentTeam->id;

        // Ensure the user has a current team and it matches the project's team_id
        if ($team_id != $project->team_id) {
            abort(403, 'You do not have access to this project.');
        }
        return view('files.create', compact('project'));
    }

    public function download($filename)
    {
        $projectfile = ProjectFile::find($filename);
        $path = storage_path('app/private/uploads/projects/' . $projectfile->file);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response()->download($path, $projectfile->original_name, ['Content-Type' => $type]);
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Log::debug("$request this is request ");

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Generate file name and store
            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();
            $fileName = 'projects_' . time() . '_' . Str::random(10) . '.' . $extension;
            $file->storeAs(ProjectFile::$FOLDER_PATH, $fileName);

            // Parse the vendor file to extract relevant data
            $parsedData = $this->parseVendorFile($file);

            // Iterate through the parsed data and save to ReportData table
            foreach ($parsedData as $data) {
                $data['project_id'] = $project->id; // Associate with the project
                $data['file_name'] = $originalFileName;
                ReportData::create($data);
            }
            // Store file metadata in the database
            $project->files()->create([
                'file' => $fileName,
                'original_name' => $originalFileName,
            ]);
        }
        
        return response()->json(['status' => 'Files uploaded successfully']);
    }

    public function destroy(ProjectFile $file)
    {
        $file->delete();
        return redirect()->back();
    }

    public function preview($filename)
    {
        $filePath = storage_path('app/private/uploads/projects/' . $filename);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Filter out null or empty headers
            $headers = array_filter($data[0], function ($header) {
                return !is_null($header) && trim($header) !== '';
            });

            Log::info('Valid Headers:', ['headers' => $headers]);

            // Remove columns with null headers from all rows
            foreach ($data as &$row) {
                $row = array_intersect_key($row, $headers);
            }
        } catch (\Exception $e) {
            // Handle the exception if the file cannot be read
            return response()->json(['error' => 'Unable to read the file.'], 500);
        }

        // Render the HTML content of the preview
        $htmlContent = view('files.preview', compact('data'))->render();

        return response()->json(['html' => $htmlContent]);
    }

    public function syncAll(Request $request, Project $project)
    {
        $start = microtime(true);
        Log::debug('Starting syncAll function');
        
        // Validate the request
        $request->validate([
            'mergeFileName' => 'required|string|max:255',
        ]);

        $mergeFileName = $request->input('mergeFileName');
        $files = $project->files()->where('enabled', true)->get();
        $inventories = $project->inventories()->get();
        // $flaskApiUrl = 'http://127.0.0.1:5000/upload'; // Local Flask API URL
        $flaskApiUrl = "http://178.156.139.16:5000/upload"; // Live Flask API URL
        Log::debug('Checked files and mergeFileName', ['mergeFileName' => $mergeFileName, 'fileCount' => $files->count(), 'inventoryCount' => $inventories->count()]);

        // Check if there are files and inventories to be sent
        if ($files->isEmpty() && $inventories->isEmpty()) {
            Log::warning('No files or inventories to synchronize');
            return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                ->with('error', 'No files or inventories to synchronize.');
        }

        $http = Http::asMultipart()->timeout(0);

        // Prepare the inventories to be sent
        foreach ($inventories as $inventory) {
            $filePath = storage_path("app/private/uploads/project_inventories/" . $inventory->file);
            Log::debug('Processing inventory file', ['filePath' => $filePath]);

            // Check if the file exists before adding to the multipart data
            if (!file_exists($filePath)) {
                Log::error('File not found', ['filePath' => $filePath]);
                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                    ->with('error', "File not found: {$filePath}");
            }

            $http = $http->attach(
                'inventory', fopen($filePath, 'r'), $inventory->original_name
            );
        }

        // Prepare the files to be sent
        foreach ($files as $file) {
            $filePath = storage_path("app/private/uploads/projects/" . $file->file);
            Log::debug('Processing file', ['filePath' => $filePath]);

            // Check if the file exists before adding to the multipart data
            if (!file_exists($filePath)) {
                Log::error('File not found', ['filePath' => $filePath]);
                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                    ->with('error', "File not found: {$filePath}");
            }

            $http = $http->attach(
                'files', fopen($filePath, 'r'), $file->original_name
            );
        }

        Log::debug('All files attached. Sending request to Flask API');

        // Make the POST request to the Flask API
        try {
            $response = $http->post($flaskApiUrl);

            // Handle the response
            if ($response->successful()) {
                $fileName = 'projects_' . time() . '_' . Str::random(10) . '.xlsx';
                Log::info("Extracted Filename", ['fileName' => $fileName]);

                // Define the final storage path
                $finalFilePath = ResultFile::$FOLDER_PATH . '/' . $fileName;

                // Store the file directly in the final storage path
                Storage::put($finalFilePath, $response->body());

                // Create a new ResultFile record
                $resultFile = ResultFile::create([
                    'project_id' => $project->id,
                    'file' => $fileName,
                    'original_name' => $mergeFileName . '.xlsx'
                ]);

                Log::info('File synchronized successfully', ['finalFilePath' => $finalFilePath]);
                $end = microtime(true); // End time
                $executionTime = $end - $start; // Calculate the execution time

                Log::info('Execution time of syncAll Function: ' . $executionTime . ' seconds');

                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'results'])
                    ->with('success', 'All files synchronized successfully.');
            } else {
                Log::error('Failed to synchronize files', ['response' => $response->body()]);
                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                    ->with('error', $response->json()['error']);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred during synchronization', ['exception' => $e->getMessage()]);
            return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                ->with('error', 'An exception occurred during synchronization.');
        }
    }

    public function manualSync(Request $request, Project $project)
    {
        $start = microtime(true);
        Log::debug('Starting manualSync function');

        // Retrieve the common columns selected
        $commonColumns = $request->input('commonColumn');
        $fileColumns = $request->input('columns');
        $mergeFileName = $request->input('mergeFileName');

        // Prepare the data for the API call
        $data = [
            'commonColumns' => $commonColumns,
            'fileColumns' => $fileColumns,
        ];

        Log::info('Data prepared for API call', ['data' => $data]);

        // Retrieve the files to attach
        $files = $project->files()->where('enabled', true)->get();

        // $flaskApiUrl = 'http://127.0.0.1:5000/manualSync'; // Local Flask API URL
        $flaskApiUrl = "http://178.156.139.16:5000/manualSync"; // Live Flask API URL

        try {
            // Create the HTTP request
            $http = Http::timeout(0)
                ->asMultipart()
                ->withHeaders(['Accept' => 'application/json'])
                ->withOptions(['verify' => false])
                ->attach('commonColumns', json_encode($commonColumns))
                ->attach('fileColumns', json_encode($fileColumns));

            foreach ($files as $file) {
                $filePath = storage_path("app/private/uploads/projects/" . $file->file);
                Log::debug('Processing file', ['filePath' => $filePath]);

                if (!file_exists($filePath)) {
                    Log::error('File not found', ['filePath' => $filePath]);
                    return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                        ->with('error', "File not found: {$filePath}");
                }

                // Attach the file using fopen
                $http = $http->attach(
                    'files', fopen($filePath, 'r'), $file->original_name
                );
            }

            // Send the API request
            $response = $http->post($flaskApiUrl);

            // Handle the response
            if ($response->successful()) {
                $fileName = 'projects_' . time() . '_' . Str::random(10) . '.xlsx';
                Log::info("Extracted Filename", ['fileName' => $fileName]);

                // Define the final storage path
                $finalFilePath = ResultFile::$FOLDER_PATH . '/' . $fileName;

                // Store the file directly in the final storage path
                Storage::put($finalFilePath, $response->body());

                // Create a new ResultFile record
                $resultFile = ResultFile::create([
                    'project_id' => $project->id,
                    'file' => $fileName,
                    'original_name' => $mergeFileName . '.xlsx'
                ]);

                Log::info('File synchronized successfully', ['finalFilePath' => $finalFilePath]);
                $end = microtime(true); // End time
                $executionTime = $end - $start; // Calculate the execution time

                Log::info('Execution time of syncAll Function: ' . $executionTime . ' seconds');

                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'results'])
                    ->with('success', 'All files synchronized successfully.');
            } else {
                Log::error('Failed to synchronize files', ['response' => $response->body()]);
                return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                    ->with('error', $response->json()['error']);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred during synchronization', ['exception' => $e->getMessage()]);
            return redirect()->route('projects.show', ['project' => $project->id, 'type' => 'files'])
                ->with('error', 'An exception occurred during synchronization.');
        }
    }


    public function toggleEnabled(Request $request)
    {
        $file = ProjectFile::find($request->file_id);
        if ($file) {
            $file->enabled = !$file->enabled;
            $file->save();
            return redirect()->back()->with('success', 'File status updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update file status.');
    }

    public function bulkAction(Request $request, $type)
    {
        $fileIds = explode(',', $request->input('file_ids'));

        if (!$fileIds) {
            return back()->with('error', 'No files selected.');
        }

        switch ($type) {
            case 'enable':
                ProjectFile::whereIn('id', $fileIds)->update(['enabled' => true]);
                return back()->with('success', 'Selected files have been enabled.');
            
            case 'disable':
                ProjectFile::whereIn('id', $fileIds)->update(['enabled' => false]);
                return back()->with('success', 'Selected files have been disabled.');
            
            case 'delete':
                ProjectFile::whereIn('id', $fileIds)->delete();
                return back()->with('success', 'Selected files have been deleted.');
            
            default:
                return back()->with('error', 'Invalid action.');
        }
    }
}
