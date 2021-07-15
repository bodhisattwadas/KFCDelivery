<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreModel;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(){
        $stores = StoreModel::all()->toArray();
        return view('admin.store',compact('stores'));
    }
    public function store(Request $request){
        $this->validate($request,[
            'store_code'=>'required|unique:store_models',
            'store_name'=>'required'
          ]);
          $store  = new StoreModel([
              'store_code'=>$request->get('store_code'),
              'store_name'=>$request->get('store_name'),
              'store_location'=>$request->get('store_location')
            ]);
          $store->save();
    
          return redirect()
                    ->route('store.index')
                    ->with('success','Store added successfully');
    }
    public function destroy($id)
    {
        $store = StoreModel::find($id);
        $store->delete();
        DB::table('store_models')->where('id',$id)->delete();
        return back();
    }
}
