<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = ['scheduled_time','order_value','paid','client_order_id',
        'drop_instruction_text','take_drop_off_picture','drop_off_picture_mandatory'];
}
