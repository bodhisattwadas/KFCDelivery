<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;
use App\Models\RiderLog;
use App\Models\User;
use App\Models\StoreRiderModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function _createOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'picup_contact_number' => 'required|numeric|digits:10',
            'store_code' => 'required|exists:store_models',
            'scheduled_time'=> 'required',
            'client_order_id'=> 'required',
            
            'name' =>'required',
            'contact_number'=>'required|numeric|digits:10',
            'address_line_1'=>'required',
            'address_line_2'=>'required',
            'city'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'pin'=>'required|numeric|digits:6',
            'paid' => [
                'required',
                Rule::in(['true','false']),
            ],
            'type' => [
                'required',
                Rule::in(['slotted','express']),
            ],

        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $riderDetails = $this->_getRider($request->get('store_code'));
            if($riderDetails){
                $order = new OrderModel([
                    'picup_contact_number' => $request->get('picup_contact_number'),
                    'store_code' => $request->get('store_code'),
                    'scheduled_time' => $request->get('scheduled_time'),
                    'order_value'=> $request->get('order_value'),
                    'paid'=> $request->get('paid'),
                    'client_order_id'=> $request->get('client_order_id'),
                    'drop_instruction_text'=> $request->get('drop_instruction_text'),
                    //'take_drop_off_picture'=> $request->get(),
                    //'drop_off_picture_mandatory'=> $request->get(),
                    'name'=> $request->get('name'),
                    'contact_number'=> $request->get('contact_number'),
                    'address_line_1'=> $request->get('address_line_1'),
                    'address_line_2'=> $request->get('address_line_2'),
                    'city'=> $request->get('city'),
                    'latitude'=> $request->get('latitude'),
                    'longitude'=> $request->get('longitude'),
                    'pin'=> $request->get('pin'),
                    'type'=> $request->get('type'),
                    'pickup_otp'=> $request->get('pickup_otp'),
                    'rider_code'=>$riderDetails['id'],
                ]);
                $order->save();
                $log = new App\Http\Controllers\RiderLogController();
                $log->_setSpecificLog($riderDetails['id'],'delivery');
                

                return response()->json([
                    "message" => 'success',
                    "data" => [
                        "status" => "accepted",
                        "rider_details"=>[
                            "name"=>$riderDetails['name'],
                            "phone"=>$riderDetails['phone_number1'],
                        ],
                        "customer_details"=>[
                            'cust_name'=> $request->get('name'),
                            'cust_phone'=> $request->get('contact_number'),
                            'cust_address_line_1'=> $request->get('address_line_1'),
                            'cust_address_line_2'=> $request->get('address_line_2'),
                            'cust_city'=> $request->get('city'),
                            'cust_latitude'=> $request->get('latitude'),
                            'cust_longitude'=> $request->get('longitude'),
                            'cust_pin'=> $request->get('pin'),
                        ],
                        "order_details"=>[
                            'slingo_order_id'=>$order->id,
                            'picup_contact_number' => $request->get('picup_contact_number'),
                            'store_code' => $request->get('store_code'),
                            'scheduled_time' => $request->get('scheduled_time'),
                            'order_value'=> $request->get('order_value'),
                            'paid'=> $request->get('paid'),
                            'client_order_id'=> $request->get('client_order_id'),
                            'drop_instruction_text'=> $request->get('drop_instruction_text'),
                        ],
                        "misc_details"=>[
                            'order_type'=> $request->get('type'),
                            'order_pickup_otp'=> $request->get('pickup_otp')
                        ],
                        
                        //"track_url"=> "sss",
                    ],
                ]);
            }else{
                return response()->json([
                    "message" => 'fail',
                    "data" => [
                        "status" => "rejected due to unavailable rider",
                        "rider_details"=>[
                            "name"=>null,
                            "phone"=>null,
                        ],
                        "slingo_order_id"=>null,
                        //"track_url"=> "sss",
                    ],
                ]);
            }

            
        }
    }
    public function _getRider($store){
        $riders = StoreRiderModel::where('store_code',$store)
                    ->join('users', function ($join) {
                        $join->on('store_rider_models.rider_code', '=', 'users.id')
                            ->where('users.verified','yes');
                        })
                    ->get()->toArray();
        $tempRider = array();
        foreach($riders as $rider){
            if(RiderLog::where('rider_code',$rider['rider_code'])->latest()->first()->status == 'in'){
                array_push($tempRider,$rider);
            }
        }
        $riders = $tempRider;
        if($riders){
            shuffle($riders);
            return User::find($riders[0]['rider_code']);
        }else{
            return null;
        }
        
    }
}
