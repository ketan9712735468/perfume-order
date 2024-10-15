<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TrackingCompanyController;
use App\Http\Controllers\StockControlStatusController;
use App\Http\Controllers\OrderDetailController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::prefix('perfume-order')->group(function () {
    Route::resource('branches', BranchController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('types', TypeController::class);
    Route::resource('tracking_companies', TrackingCompanyController::class);
    Route::resource('stock_control_statuses', StockControlStatusController::class);
    Route::resource('order_details', OrderDetailController::class);
    Route::post('/order-details/bulk-delete', [OrderDetailController::class, 'bulkDelete'])
        ->name('order_details.bulk_delete');
});
