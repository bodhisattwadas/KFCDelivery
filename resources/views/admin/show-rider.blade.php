<?php 
use App\Models\User;
use App\Models\StoreModel;
use App\Models\StoreRiderModel;
$details = User::where('id',$rider)->get()->first();
$stores = StoreModel::all()->toArray();
//var_dump($details);
?>
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <div class="row">
        
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><h4>Rider Details</h4></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Rider Name : {{$details['name']}}</p>
                            <p>Rider Email : {{$details['email']}}</p>
                            <p>Rider Phone Number : {{$details['phone_number1']}}</p>
                            <p>Rider Alternate Number : {{$details['phone_number2']}}</p>
                            <p>AAdhar Number : {{$details['aadhar_number']}}</p>
                            <p>Driving Licence Number : {{$details['dl_number']}}</p>
                            <p>Rider Location : {{$details['location']}}</p>
                            <p>Is Verified : {{$details['verified']}}</p>
                        </div>
                        <div class="col-md-6">
                            @if($details['aadhar_picture'])
                            <div class="card">
                                <div class="card-header">
                                    AAdhar Card
                                </div>
                                <div class="card-body">
                                    <img src="{{URL::to('/')}}/storage/app/{{$details['aadhar_picture']}}"
                                     class="img-rounded" width="100%" >
                                </div>
                            </div>
                            @endif
                        {{-- </div>
                        <div class="col-md-3"> --}}
                            @if($details['dl_picture'])
                            <div class="card">
                                <div class="card-header">
                                    Driving License Card
                                </div>
                                <div class="card-body">
                                    <img src="{{URL::to('/')}}/storage/app/{{$details['dl_picture']}}"
                                     class="img-rounded" width="100%" >
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($details['verified'] == 'no')
                <form method="POST" action="{{url('verify.rider')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$rider}}">
                    <button type="submit" class="btn btn-success btn-sm">Verify Rider</button>
                </form>
                @else
                <form method="POST" action="{{url('block.rider')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$rider}}">
                    <button type="submit" class="btn btn-danger btn-sm">Block Rider</button>
                </form>
                @endif
                </div>
                
            </div>
            
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5>Assigned Stores</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('update.rider.store')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="rider" value="{{$rider}}">
                        <div class="form-group">
                            <select style="width:100%;" class="js-example-basic-multiple form-control" name="stores[]" multiple="multiple">
                            @foreach($stores as $store)
                            <option value="{{$store['store_code']}}"
                             @if(StoreRiderModel::where([['rider_code',$rider],['store_code',$store['store_code']]])->get()->count() != 0 ) selected @endif>
                             {{$store['store_name']}}
                            </option>
                            @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
@stop

@section('css')
    
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    </script>
@stop