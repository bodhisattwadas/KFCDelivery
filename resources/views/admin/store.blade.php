@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Store</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Store List</div>
            <div class="card-body">
                <table class="table table-bordered mDataTable" width="100%">
                    <thead>
                      <tr>
                          <th>Store Code</th>
                          <th>Store Name</th>
                          <th>Location</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($stores as  $element)
                        <tr>
                            <td>{{$element['store_code']}}</td>
                            <td>{{$element['store_name']}}</td>
                            <td>{{$element['store_location']}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Add Store</div>
            <div class="card-body">
                @include('admin.form-error-success')
                <form method="POST" action="{{url('store')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label">Store Code</label>
                        <input type="text" class="form-control input-sm" name="store_code">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Store Name</label>
                        <input type="text" class="form-control input-sm" name="store_name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Store Location</label>
                        <input type="text" class="form-control input-sm" name="store_location">
                    </div>
                    <button class="btn btn-sm btn-success btn-block">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">-->
@stop

@section('js')
    <!--<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>-->
    <script>
        $(".mDataTable").DataTable();
    </script>
@stop