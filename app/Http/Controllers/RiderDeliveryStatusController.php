<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiderDeliveryStatusModel;
use App\Models\OrderModel;
use App\Models\User;
use App\Models\RiderMovementStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Http\Controllers\RiderLogController;
use Haversini\Haversini;
use Log;

class RiderDeliveryStatusController extends Controller
{
    //'email','order_id','order_status','latitude','longitude'
    public function _getStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'order_id' => 'required',
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $status = RiderDeliveryStatusModel::where('order_id',$request->get('order_id'))->latest()->first();
            Log::debug($status->id);
            if(!$status){
                return response()->json([
                    "status" => 'fail',
                    "message" => "No status data found",
                ]);
            }else{
                return response()->json([
                    "status" => 'success',
                    "message" => [
                        "_time" => $status->created_at,
                        "rider_name" => User::where("email",$status->email)->get()->first()->name,
                        "order_id" => $request->get('order_id'),
                        "order_status"=> strtoupper($status->order_status),
                        "rider_latitude" => $status->latitude,
                        "rider_longitude" => $status->longitude,
                    ]
                ]);
            }
        }
    }
    public function _getMovementStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'order_id' => 'required',
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $status = RiderMovementStatusModel::where('order_id',$request->get('order_id'))->latest()->first()->get();
            if(!$status){
                return response()->json([
                    "status" => 'fail',
                    "message" => "No status data found",
                ]);
            }else{
                return response()->json([
                    "status" => 'success',
                    "message" => [
                        "_time" => $status[0]->created_at,
                        "rider_name" => User::where("email",$status[0]->email)->get()->first()->name,
                        "order_id" => $request->get('order_id'),
                        "rider_latitude" => $status[0]->latitude,
                        "rider_longitude" => $status[0]->longitude,

                    ]
                ]);
            }
        }
    }
    public function _setStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            //'order_id' => 'required',
            'order_status'=>['required',Rule::in(['allocated','arrived','dispatched',
                'arrived_customer_doorstep','delivered','returned_to_seller','cancelled','cancelled_by_customer'])],
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-90,90',
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
                    "status" => 'fail',
                    "message" => 'some error occurred',
                ]);
            }else{
                $rsModel = new RiderDeliveryStatusModel([
                    'email' => $request->get('email'),
                    'order_id' => $order->id,
                    'order_status'=>$request->get('order_status'),
                    'latitude' => $request->get('latitude'),
                    'longitude' => $request->get('longitude'),
                ]);
                $rsModel->save();
                // [CANCELLED, RETURNED_TO_SELLER, DELIVERED, ACCEPTED, CANCELLED_BY_CUSTOMER, ARRIVED_CUSTOMER_DOORSTEP, ARRIVED, DISPATCHED, ALLOTTED]
                

                if($request->get('order_status') == 'returned_to_seller'){
                    (new RiderLogController())->_setSpecificLog($order->rider_code,'in');
                }
                $order->order_status = $request->get('order_status');
                $order->save();
                /** Update to server */
                $this->_pushDeliveryStatusData($order,$request->get('latitude'),$request->get('longitude'));

                return response()->json([
                    "status" => 'success',
                    "message" => 'updated',
                ]);

            };
            
        }
    }
    public function _setMovementStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-90,90',
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
                    "status" => 'fail',
                    "message" => 'some error occurred',
                ]);
            }
                else{
                    $rsModel = new RiderMovementStatusModel([
                        'email' => $request->get('email'),
                        'order_id' => $order->id,
                        'latitude' => $request->get('latitude'),
                        'longitude' => $request->get('longitude'),
                    ]);
                    $rsModel->save();
                     $this->_pushDeliveryLocationData($order,$request->get('latitude'),$request->get('longitude'));
                    return response()->json([
                        "status" => 'success',
                        "message" => 'updated',
                    ]);
            }
        }
    }

    public function _pushDeliveryStatusData($order,$latitude,$longitude){
            $distance = floor(Haversini::calculate(
                $latitude,
                $longitude,
                $order->latitude,
                $order->longitude
            ));
            Log::debug("Distance: ".$distance);
            Log::debug("Time: ".$distance*6);
            if($order->order_status == 'allocated') $order_status = 'allotted';
            $postData = array(
                // "allot_time" => '2017-11-30T09:25:17.000000Z',
                "allot_time" => $order->created_at->format('c'),
                "rider_name" => User::find($order->rider_code)->name,
                "slingo_order_id" => $order->id,
                "client_order_id" => $order->client_order_id,
                "order_status" => ($order->order_status == 'allocated')?"ALLOTTED":strtoupper($order->order_status),
                "rider_contact" => User::find($order->rider_code)->phone_number1,
                "rider_latitude" => $latitude,
                "rider_longitude" => $longitude,
                "pickup_eta" => ($order->order_status == 'allocated')?rand(1,7):0,
                "drop_eta" => $distance*6+rand(0,8)
            );
            Log::debug($postData);
            $ch = curl_init(env('PRODUCTION_URL').'/_ah/api/externalApi/v1/tygor/task/update');
            curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'client_id: '.env('PRODUCTION_CLIENT_ID'),
                    'Content-Type: application/json',
                    'referer: '.env('PRODUCTION_REFERRER')
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if($response === FALSE){
                die(curl_error($ch));
            }

            // Decode the response
            $responseData = json_decode($response, TRUE);

            // Close the cURL handler
            curl_close($ch);

            // Print the date from the response
            //echo $responseData['published'];
            Log::debug($responseData);
    }

    public function _pushDeliveryLocationData($order,$latitude,$longitude){
           
            $postData = array(
                 "order_id" => $order->id,
                 "rider_name" => User::find($order->rider_code)->name,
                 "rider_longitude" => $longitude , 
                 "time" => "1000",
                 "rider_latitude" => $latitude,
                 "location_accuracy" => "100"
            );
            Log::debug($postData);
            $ch = curl_init(env('PRODUCTION_URL').'/_ah/api/externalApi/v1/update/location/riderLocation');
            curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'client_id: '.env('PRODUCTION_CLIENT_ID'),
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if($response === FALSE){
                die(curl_error($ch));
            }

            // Decode the response
            $responseData = json_decode($response, TRUE);

            // Close the cURL handler
            curl_close($ch);

            // Print the date from the response
            //echo $responseData['published'];
            Log::debug($responseData);
    }

    
}
