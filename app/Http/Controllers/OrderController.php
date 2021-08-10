<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;

class OrderController extends Controller
{
    public function _createOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'picup_contact_number' => 'required',
            'store_code' => 'required',
            
                            'scheduled_time','order_value','paid','client_order_id',
                            'drop_instruction_text','take_drop_off_picture','drop_off_picture_mandatory',
                            'name','contact_number','address_line_1','address_line_2','city',
                            'latitude','longitude','pin','type','pickup_otp'
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }
    }
}
