<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreRiderModel;

class StoreRiderModelController extends Controller
{
    public function store(Request $request){
        $stores = $request->get('stores') ;
        StoreRiderModel::where('rider_code',$request->get('rider'))->delete();
        foreach($stores as $store){
            $srModel = new StoreRiderModel([
                'store_code' => $store,
                'rider_code' => $request->get('rider'),
            ]);
            $srModel->save();
        }
        return redirect()->route('rider.index');
    }
}
