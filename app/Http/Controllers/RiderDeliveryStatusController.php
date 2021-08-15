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
            $status = RiderDeliveryStatusModel::where('order_id',$request->get('order_id'))->latest()->first()->get();
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
                        "order_status"=> strtoupper($status[0]->order_status),
                        "rider_latitude" => $status[0]->latitude,
                        "rider_longitude" => $status[0]->longitude,

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
                'arrived_customer_doorstep','delivered','returned_to_seller'])],
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
                if($request->get('order_status') == 'returned_to_seller'){
                    (new RiderLogController())->_setSpecificLog($order->rider_code,'in');
                }
                $order->order_status = $request->get('order_status');
                $order->save();
                
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
            //'order_id' => 'required',
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
                    return response()->json([
                        "status" => 'success',
                        "message" => 'updated',
                    ]);
            }
        }
    }

    
}
