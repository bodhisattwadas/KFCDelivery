<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderModel;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function _getLoginStatus(Request $request){
        $user = User::where('email',$request->get('email'))
                    ->get()->first();
        if(!is_null($user)){
            if(Hash::check($request->get('password'), $user['password'])){
                $status = 'success';
                $message = 'user found!!';
            }
            else {
                $status = 'fail';
                $message = 'user found , but error in password';
            }
        }else{
            $status = 'fail';
            $message = 'user not found';
        }
        return json_encode(
            [
                'status'=>$status,
                'message'=>$message
            ]
        );
    }
    public function _createUser(Request $request){
        if(is_null($request->get('name'))){
            return json_encode([
                'status'=>'fail',
                'message'=>'name field empty'
            ]);
        }
        elseif(is_null($request->get('email'))){
            return json_encode([
                'status'=>'fail',
                'message'=>'email field empty'
            ]);
        }
        elseif(is_null($request->get('password'))){
            return json_encode([
                'status'=>'fail',
                'message'=>'password field empty'
            ]);
        }
        elseif(User::where('email',$request->get('email'))->get()->first()){
            return json_encode([
                'status'=>'fail',
                'message'=>'email already taken'
            ]);
        }else{
            $user = new User([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'),),
                'verified'=>'no',
                'type'=>'rider'
            ]);
            $user->save();
            return json_encode([
                'status'=>'success',
                'message'=>'user created successfully'
            ]);
        }
        
    }
    public function _getVerifiedStatus(Request $request){
        $status = User::where('email',$request->get('email'))->get()->first()->verified;
        return json_encode([
            'status'=>$status
        ]);
    }
/**
 * 
 * 'phone_number',
        'aadhar_number',
        'dl_number',
        'location',
 */
    public function _updateProfile(Request $request){
        $user = User::find(User::where('email',$request->get('email'))->get()->first()->id);
        if($request->hasFile('aadhar_picture')){
            $file = $request->file('aadhar_picture');
            $filename = 'aadhar-photo-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename);
            $user->aadhar_picture = $path;
        }
        if($request->hasFile('dl_picture')){
            $file = $request->file('dl_picture');
            $filename = 'dl-photo-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename);
            $user->dl_picture = $path;
        }
        if($request->has('phone_number1')){
            $user->phone_number1 = $request->get('phone_number1');
        }
        if($request->has('phone_number2')){
            $user->phone_number2 = $request->get('phone_number2');
        }
        if($request->has('aadhar_number')){
            $user->aadhar_number = $request->get('aadhar_number');
        }
        if($request->has('dl_number')){
            $user->dl_number = $request->get('dl_number');
        }
        if($request->has('location')){
            $user->location = $request->get('location');
        }
        
        $user->save();
        return json_encode([
            'status'=>'success'
        ]);
        

    }
    public function _createOrder(Request $request){
        if(!$request->has('order_id') || $request->get('order_id')==''){
            return json_encode([
                'status'=>'fail',
                'message'=>'order id is not specified'
            ]);
        }elseif(!$request->has('store_code') || $request->get('store_code')==''){
            return json_encode([
                'status'=>'fail',
                'message'=>'store code is not specified'
            ]);
        }elseif(OrderModel::where('order_id',$request->get('order_id'))->get()->count() != 0){
            return json_encode([
                'status'=>'fail',
                'message'=>'order id is already generated'
            ]);
        }elseif(OrderModel::where('order_id',$request->get('order_id'))->get()->count() == 0){    
            $order = new OrderModel([
                'order_id'=>$request->get('order_id'),
                'store_code'=>$request->get('store_code')
            ]);
            $order->save();
            return json_encode([
                'status'=>'success',
                'message'=>'order generated'
            ]);
        }else{
            return json_encode([
                'status'=>'fail',
                'message'=>'some uknown error occurred'
            ]);
        }
    }
}
