<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $vendorData = $request->all();
        $vendorData['enabled'] = $request->has('enabled') ? true : false;
        $vendor = Vendor::create($vendorData);
        return redirect()->route('vendors.index');
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $vendorData = $request->all();
        $vendorData['enabled'] = $request->has('enabled') ? true : false;
        $vendor->update($vendorData);
        return redirect()->route('vendors.index');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index');
    }
}
