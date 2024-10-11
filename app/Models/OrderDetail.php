<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'employee_id',
        'email_date',
        'response_date',
        'vendor_id',
        'type_id',
        'sales_order',
        'invoice_number',
        'freight',
        'total_amount',
        'paid_date',
        'paid_amount',
        'variants',
        'sb',
        'rb',
        'units',
        'received',
        'delivery_date',
        'tracking_company_id',
        'tracking_number',
        'note',
        'stock_control_status_id',
        'order_number',
    ];

    protected $casts = [
        'email_date' => 'date',
        'response_date' => 'date',
        'paid_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
    
    public function type() {
        return $this->belongsTo(Type::class);
    }
    
    public function trackingCompany() {
        return $this->belongsTo(TrackingCompany::class);
    }

    public function stock_control_status() {
        return $this->belongsTo(StockControlStatus::class);
    }
    
}
