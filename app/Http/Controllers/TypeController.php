<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('types.index', compact('types'));
    }

    public function create()
    {
        return view('types.create');
    }

    public function store(Request $request)
    {
        $type = Type::create($request->all());
        return redirect()->route('types.index');
    }

    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    public function update(Request $request, Type $type)
    {
        $type->update($request->all());
        return redirect()->route('types.index');
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route('types.index');
    }
}
