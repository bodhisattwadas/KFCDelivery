<?php 
use App\Models\User;
$details = User::where('id',$rider)->get()->first();
//var_dump($details);
?>
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
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
                        <div class="col-md-3">
                            @if($details['aadhar_picture'])
                            <div class="card">
                                <div class="card-header">
                                    AAdhar Card
                                </div>
                                <div class="card-body">
                                    <img src="{{URL::to('/')}}/storage/app/{{$details['aadhar_picture']}}" class="img-rounded" width="200" >
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            @if($details['dl_picture'])
                            <div class="card">
                                <div class="card-header">
                                    Driving License Card
                                </div>
                                <div class="card-body">
                                    <img src="{{URL::to('/')}}/storage/app/{{$details['dl_picture']}}" class="img-rounded" width="200" >
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('js')
    <script>  </script>
@stop