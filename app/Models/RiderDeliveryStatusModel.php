<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderDeliveryStatusModel extends Model
{
    use HasFactory;
    protected $fillable = ['email','order_id','order_status','latitude','longitude'];
}
