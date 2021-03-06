<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderMovementStatusModel extends Model
{
    use HasFactory;
    protected $fillable = ['email','order_id','latitude','longitude'];
}
