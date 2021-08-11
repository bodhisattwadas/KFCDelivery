<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiderDeliveryStatusModel;
use App\Models\RiderMovementStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RiderDeliveryStatusController extends Controller
{
    //'email','order_id','order_status','latitude','longitude'
    public function _setStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            'order_id' => 'required',
            'order_status'=>['required',Rule::in(['arrived','dispatched','arrived_customer_doorstep','delivered','cancelled','cancelled_by_customer','returned_to_seller'])],
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-90,90',
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $rsModel = new RiderDeliveryStatusModel([
            'email' => $request->get('email'),
            'order_id' => $request->get('order_id'),
            'order_status'=>$request->get('order_status'),
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
    public function _setMovementStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            'order_id' => 'required',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-90,90',
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $rsModel = new RiderMovementStatusModel([
            'email' => $request->get('email'),
            'order_id' => $request->get('order_id'),
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
