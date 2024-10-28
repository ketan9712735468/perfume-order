<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_sku',
        'name',
        'brand',
        'category',
        'price',
        'project_id',
        'file_name'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
