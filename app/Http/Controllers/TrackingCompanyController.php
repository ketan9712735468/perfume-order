<?php

namespace App\Http\Controllers;

use App\Models\TrackingCompany;
use Illuminate\Http\Request;

class TrackingCompanyController extends Controller
{
    public function index()
    {
        $tracking_companies = TrackingCompany::all();
        return view('tracking_companies.index', compact('tracking_companies'));
    }

    public function create()
    {
        return view('tracking_companies.create');
    }

    public function store(Request $request)
    {
        $tracking_company = TrackingCompany::create($request->all());
        return redirect()->route('tracking_companies.index');
    }

    public function edit(TrackingCompany $tracking_company)
    {
        return view('tracking_companies.edit', compact('tracking_company'));
    }

    public function update(Request $request, TrackingCompany $tracking_company)
    {
        $tracking_company->update($request->all());
        return redirect()->route('tracking_companies.index');
    }

    public function destroy(TrackingCompany $tracking_company)
    {
        $tracking_company->delete();
        return redirect()->route('tracking_companies.index');
    }
}
