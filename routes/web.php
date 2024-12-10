<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TrackingCompanyController;
use App\Http\Controllers\StockControlStatusController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ResultFileController;
use App\Http\Controllers\ProjectInventoryController;
use App\Http\Controllers\ReportController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('perfume-order')->group(function () {
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



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('perfume-service')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('projects.files', ProjectFileController::class)->shallow();
    Route::resource('projects.inventory', ProjectInventoryController::class)->shallow();
    Route::resource('projects.resultFiles', ResultFileController::class)->shallow();
    Route::get('/preview-excel/{filename}', [ProjectFileController::class, 'preview'])->name('excel.preview');
    Route::get('/download/{filename}', [ProjectFileController::class, 'download'])->name('download');
    Route::post('/bulk-action/{type}', [ProjectFileController::class, 'bulkAction'])->name('bulk_action');
    Route::get('/preview-result/{filename}', [ResultFileController::class, 'preview'])->name('result.preview');
    Route::get('/download-result/{filename}', [ResultFileController::class, 'download'])->name('result_download');
    Route::post('/projects/{project}/files/sync', [ProjectFileController::class, 'syncAll'])->name('projects.files.syncAll');
    Route::post('/projects/{project}/files/manual-sync', [ProjectFileController::class, 'manualSync'])->name('projects.files.manualSync');
    Route::post('/files/toggle-enabled', [ProjectFileController::class, 'toggleEnabled']);
    Route::get('/preview-inventory/{filename}', [ProjectInventoryController::class, 'preview'])->name('inventory.preview');
    Route::get('/download-inventory/{filename}', [ProjectInventoryController::class, 'download'])->name('inventory_download');
    Route::get('/check-inventory-file/{projectId}', [ProjectController::class, 'checkInventoryFile']);
    Route::resource('reports', ReportController::class);
});
