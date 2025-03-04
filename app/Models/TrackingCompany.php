<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingCompany extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'link', 'note', 'enabled'];
}
