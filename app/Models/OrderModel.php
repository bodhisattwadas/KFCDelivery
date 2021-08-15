<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    protected $fillable = [/**'order_id',*/'picup_contact_number','store_code',
                            'scheduled_time','order_value','paid','client_order_id',
                            'drop_instruction_text',
                            'name','contact_number','address_line_1','address_line_2','city',
                            'latitude','longitude','pin','type','pickup_otp','rider_code',
                            'order_status','cancel_description'
                        ];
}
