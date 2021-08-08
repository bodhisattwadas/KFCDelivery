<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiscModel extends Model
{
    use HasFactory;
    protected $fillable = ['type','pickup_otp'];
}
