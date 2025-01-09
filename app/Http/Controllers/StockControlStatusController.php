<?php

namespace App\Http\Controllers;

use App\Models\StockControlStatus;
use Illuminate\Http\Request;

class StockControlStatusController extends Controller
{
    public function index()
    {
        $stock_control_statuses = StockControlStatus::orderBy('id', 'asc')->get();
        return view('stock_control_statuses.index', compact('stock_control_statuses'));
    }

    public function create()
    {
        return view('stock_control_statuses.create');
    }

    public function store(Request $request)
    {
        $stockcontrolstatusData = $request->all();
        $stockcontrolstatusData['enabled'] = $request->has('enabled') ? true : false;
        $stock_control_status = StockControlStatus::create($stockcontrolstatusData);
        return redirect()->route('stock_control_statuses.index');
    }

    public function edit(StockControlStatus $stock_control_status)
    {
        return view('stock_control_statuses.edit', compact('stock_control_status'));
    }

    public function update(Request $request, StockControlStatus $stock_control_status)
    {
        $stockcontrolstatusData = $request->all();
        $stockcontrolstatusData['enabled'] = $request->has('enabled') ? true : false;
        $stock_control_status->update($stockcontrolstatusData);
        return redirect()->route('stock_control_statuses.index');
    }

    public function destroy(StockControlStatus $stock_control_status)
    {
        $stock_control_status->delete();
        return redirect()->route('stock_control_statuses.index');
    }
}
