<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;
use App\Models\RiderLog;
use App\Models\User;
use App\Models\StoreRiderModel;
use App\Models\RiderDeliveryStatusModel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\RiderLogController;
use Illuminate\Validation\Rule;

use Log;

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
            'client_order_id'=> 'required|unique:order_models',
            
            'name' =>'required',
            'contact_number'=>'required|numeric|digits:10',
            'address_line_1'=>'required',
            'address_line_2'=>'required',
            'city'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'pin'=>'numeric|digits:6',
            //'pin'=>'required|numeric|digits:6',
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
                    'order_status'=>'allocated',
                ]);
                $order->save();

                (new RiderLogController())->_setSpecificLog($riderDetails['id'],'delivery');
                $this->_sendFCM(str_replace("@", "", $riderDetails['email']),"New Order","A new order has been arrived.");

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
                Log::debug("Rejected due to unavailble rider. ");
                return response()->json([
                    "message" => 'fail',
                    "data" => [
                        "status" => "rejected due to unavailable rider",
                        "rider_details"=>[
                            "name"=>null,
                            "phone"=>null,
                        ],
                        "slingo_order_id"=>null,
                    ],
                ]);
            }
        }
    }
    public function _getOrderDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email'=>'required|email|exists:users',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            //'allocated','arrived','dispatched','arrived_customer_doorstep','delivered','returned_to_seller'
            

            $order = OrderModel::where("rider_code",User::where('email',$request->get('email'))->get()->first()->id)
            ->whereIn('order_status',['allocated','arrived','dispatched','arrived_customer_doorstep','delivered','cancelled','cancelled_by_customer'])
            ->get()->first();
            if(!$order){
                return response()->json([
                    "message" => 'fail',
                    "data" => [],
                ]);
            }else{
                return response()->json([
                    "message" => 'success',
                    "data" => [
                        'order_id'=>$order->id,
                        'order_status'=>$order->order_status,
                        'picup_contact_number' => $order->picup_contact_number,
                        'pickup_otp'=> $order->pickup_otp,
                        'name'=> $order->name,
                        'contact_number'=> $order->contact_number,
                        'address_line_1'=> $order->address_line_1,
                        'address_line_2'=> $order->address_line_2,
                        'city'=> $order->city,
                        'latitude'=> $order->latitude,
                        'longitude'=> $order->longitude,
                        'pin'=> $order->pin,
                        'drop_instruction_text'=> $order->drop_instruction_text,
                        'order_value' => $order->order_value,
                        'paid'=> $order->paid,
                    ],
                ]);
            }
        }

    }
    public function _getDeliveryStatusArray(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email'=>'required|email|exists:users',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $order = OrderModel::where("rider_code",User::where('email',$request->get('email'))->get()->first()->id)
            ->whereIn('order_status',['allocated','arrived','dispatched','arrived_customer_doorstep','delivered','cancelled','cancelled_by_customer'])
            ->get()->first();
            if(!$order){
                return response()->json([
                    "message" => 'fail',
                    "data" => [],
                ]);
            }else{
                // Log::debug([
                //                 ["email",$request->get('email')],
                //                 ["order_id",$order->id],
                //                 ["order_status",'allocated'],
                //             ]);
                return response()->json([
                    // 'allocated','arrived','dispatched','arrived_customer_doorstep','delivered','cancelled','cancelled_by_customer'
                    "message" => 'success',
                    "data" => [
                        'allocated' => (RiderDeliveryStatusModel::where([
                                ["email",$request->get('email')],
                                ["order_id",$order->id],
                                ["order_status",'allocated'],
                            ])->get()->count() != 0)?true:false,
                        'arrived' => (RiderDeliveryStatusModel::where([
                                ["email",$request->get('email')],
                                ["order_id",$order->id],
                                ["order_status",'arrived'],
                            ])->get()->count() != 0)?true:false,
                        'dispatched' => (RiderDeliveryStatusModel::where([
                                ["email",$request->get('email')],
                                ["order_id",$order->id],
                                ["order_status",'dispatched'],
                            ])->get()->count() != 0)?true:false,
                        'arrived_customer_doorstep' => (RiderDeliveryStatusModel::where([
                                ["email",$request->get('email')],
                                ["order_id",$order->id],
                                ["order_status",'arrived_customer_doorstep'],
                            ])->get()->count() != 0)?true:false,
                        'delivered' => (RiderDeliveryStatusModel::where([
                                ["email",$request->get('email')],
                                ["order_id",$order->id],
                                ["order_status",'delivered'],
                            ])->get()->count() != 0)?true:false,
                        'cancelled' => ($order->order_status == 'cancelled')?true:false,
                        'cancelled_by_customer' => ($order->order_status == 'cancelled_by_customer')?true:false,
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
            Log::debug($rider['rider_code']);
            try{
                if(RiderLog::where('rider_code',$rider['rider_code'])->latest()->first()->status == 'in'){
                    array_push($tempRider,$rider);
                }
            }catch(\Exception $e){
                Log::debug($e->getMessage());
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
    public function _sendFCM($email,$header,$body){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $apiKey = env('FIREBASE_KEY');
        $headers = array(
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );
        $notify = [
            'title' => $header,
            'body' => $body,
        ];
        $apiBody = [
            'notification' => $notify,
            'to' => '/topics/' . $email
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
        $result = curl_exec($ch);
        //print($result);
        curl_close($ch);
        //return $result;
    }
    public function _cancelOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'order_id'=>'required|exists:order_models,id',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $order = OrderModel::find($request->get('order_id'));
            $order->order_status = 'cancelled';
            $order->cancel_description = $request->get('cancel_description');
            $order->save();

            $rsModel = new RiderDeliveryStatusModel([
                    'email' => User::find($order->rider_code)->email,
                    'order_id' => $order->id,
                    'order_status'=>'cancelled',
                    'latitude' => '0.00',
                    'longitude' => '0.00',
                ]);
            $rsModel->save();
            $this->_sendFCM(str_replace("@", "", User::find($order->rider_code)->email),"Order Cancelled","This order is cancelled");
            //['email','order_id','order_status','latitude','longitude'];
            return response()->json([
                "status" => 'success',
                "message" => 'order cancelled successfully',
            ]);
        }
    }
    public function _cancelOrderByCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'order_id'=>'required|exists:order_models,id',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $order = OrderModel::find($request->get('order_id'));
            $order->order_status = 'cancelled';
            $order->cancel_description = $request->get('cancel_description');
            $order->save();

            $rsModel = new RiderDeliveryStatusModel([
                    'email' => User::find($order->rider_code)->email,
                    'order_id' => $order->id,
                    'order_status'=>'cancelled_by_customer',
                    'latitude' => '0.00',
                    'longitude' => '0.00',
                ]);
            $rsModel->save();
            $this->_sendFCM(str_replace("@", "", User::find($order->rider_code)->email),"Order Cancelled","This order is cancelled");
            return response()->json([
                "status" => 'success',
                "message" => 'order cancelled successfully',
            ]);
        }
    }
}
