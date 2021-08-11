<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderLog extends Model
{
    use HasFactory;
    protected $fillable = ['rider_code','log_time','status'];
}
