<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Validation\Rule;

class AjaxController extends Controller
{
    public function _getLoginStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }
        $user = User::where('email',$request->get('email'))
                    ->get()->first();

        if(Hash::check($request->get('password'), $user['password'])){
                        return response()->json([
                            "status" => 'success',
                            "message" => 'User found and success in login',
            ]);
        }else{
            return response()->json([
                "status" => 'fail',
                "message" => 'Error occurred',
            ]);
        }
    }
    public function _createUser(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:50',
            'password' => 'required',
            'phone_number'=>'required|numeric|digits:10'
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }
        try{
            $user = new User([
                       'name' => $request->get('name'),
                       'email' => $request->get('email'),
                       'password' => Hash::make($request->get('password')),
                       'phone_number1'=>$request->get('phone_number'),
                       'verified'=>'no',
                       'role'=>'rider'
                    ]);
            $user->save();
            return response()->json([
                'status'=>'success',
                'message'=>'registered successfully',
            ]);
        }
        catch(Exception $e){
            return response()->json([
                "status" => "fail",
                "message" => "Unable to register user"
            ]);
        }
    }
    public function _getVerifiedStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }
        $status = User::where('email',$request->get('email'))->get()->first()->verified;
        return json_encode([
            'status'=>$status
        ]);
    }
    public function _updateProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email|exists:users',
            'aadhar_picture' => 'required',
            'dl_picture' => 'required',
            'phone_number1' => 'required',
            'phone_number2' => 'required',
            'aadhar_number' => 'required',
            'dl_number' => 'required',
            'location' => 'required',

        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => 'fail',
                //"error" => $validator->errors(),
            ]);
        }

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
            'status'=>'success',
            'message'=>"Profile updated successfully",
            //'erroor'=>[],
        ]);
        

    }
    public function _checkProfileUpdateStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'api_token'=>[
                'required',
                Rule::in([env('API_KEY')]),
            ],
            'email' => 'required|email'
        ]);
         
        if($validator->fails()){
            return response()->json([
                "status" => 'fail',
                "message" => $validator->errors(),
            ]);
        }else{
            $user = User::find(User::where('email',$request->get('email'))->get()->first()->id);
            if(
                !$user->phone_number1 || !$user->phone_number2 ||
                !$user->aadhar_number || !$user->dl_number ||
                !$user->location || !$user->aadhar_picture ||
                !$user->dl_picture
            ){
                return response()->json([
                    "status" => 'fail',
                    "message" => "Not uploaded details",
                ]);
            }else{
                return response()->json([
                    "status" => 'success',
                    "message" => "Uploaded details",
                ]);
            }
        }
    }

    

    
}
