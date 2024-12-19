<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_team = Auth::user()->currentTeam;
        $projects = Project::where('team_id', $user_team->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentTeam = $user->currentTeam;
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        // Create the project with the team_id
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'team_id' => $currentTeam->id,
        ]);
        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the current team of the user
        $team_id = $user->currentTeam->id;

        // Ensure the user has a current team and it matches the project's team_id
        if ($team_id != $project->team_id) {
            abort(403, 'You do not have access to this project.');
        }
    
        // Fetch all project files
        $files = $project->files()->orderBy('id', 'asc')->get();
        $fileDetails = [];
        // Render the project view
        return view('projects.show', compact('project', 'fileDetails', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);


        $project->update($request->all());
        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index');
    }

    public function checkInventoryFile($projectId)
    {
        try {
            // Fetch the project with files and inventories
            $project = Project::with(['files' => function ($query) {
                $query->where('enabled', true);
            }, 'inventories'])->findOrFail($projectId);

            // If inventories exist, return only inventoryAvailable as true
            if ($project->inventories->isNotEmpty()) {
                return response()->json([
                    'inventoryAvailable' => true,
                    'fileDetails' => [], // Return an empty array to minimize response size
                ], 200);
            }

            // Get columns for each file
            $fileDetails = [];
            foreach ($project->files as $file) {
                $path = storage_path('app/private/uploads/projects/' . $file->file);
                if (!file_exists($path)) {
                    continue; // Skip if the file does not exist
                }

                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $header = [];

                if ($extension === 'csv') {
                    $csv = \League\Csv\Reader::createFromPath($path, 'r');
                    $csv->setHeaderOffset(0); // Set to 0 for CSV with headers
                    $header = $csv->getHeader();
                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                    $sheet = $spreadsheet->getActiveSheet();
                    $header = $sheet->rangeToArray('1:1')[0]; // Read the first row as header
                }

                // Filter out empty columns
                $fileDetails[] = [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'columns' => array_filter($header, function ($column) {
                        return !empty(trim($column));
                    }),
                ];
            }

            return response()->json([
                'inventoryAvailable' => false,
                'fileDetails' => $fileDetails,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch file details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
