<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreRiderModel extends Model
{
    use HasFactory;
    protected $fillable = ['store_code','rider_code'];
}
