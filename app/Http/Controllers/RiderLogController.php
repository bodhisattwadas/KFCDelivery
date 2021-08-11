<?php

namespace App\Http\Controllers;

use App\Models\RiderLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RiderLogController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function _setLog(Request $request){
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
        $rLog = new RiderLog([
            'rider_code'=>User::where('email',$request->get('email'))->get()->first()->id,
            'status' => $request->get('status'),
        ]);
        $rLog->save();
        return response()->json([
            "status" => 'success',
            "message" => $request->get('status'),
        ]);
    }
    public function _getLog(Request $request){
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
        $rider = User::where('email',$request->get('email'))->get()->first()->id;

        $status = RiderLog::where('rider_code',$rider)->latest()->first();
        if(!$status){
            $rLog = new RiderLog([
                'rider_code'=>$rider,
                'status' => 'out',
            ]);
            $rLog->save();
        }
        $status = RiderLog::where('rider_code',$rider)->latest()->first();
        return response()->json([
            "status" => 'success',
            "message" => $status->status,
        ]);
    }
}
