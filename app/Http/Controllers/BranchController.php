<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $branchData = $request->all();
        $branchData['enabled'] = $request->has('enabled') ? true : false;

        $branch = Branch::create($branchData);

        return redirect()->route('branches.index');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $branchData = $request->all();
        $branchData['enabled'] = $request->has('enabled') ? true : false;

        $branch->update($branchData);

        return redirect()->route('branches.index');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index');
    }
}