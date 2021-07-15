@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Store</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Store List</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered mDataTable" width="100%">
                  <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Verified</th>
                        <th></th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($riders as  $element)
                      <tr>
                          <td>{{$element['name']}}</td>
                          <td>
                              Ph 1:{{$element['phone_number1']}}
                              <br>
                              Ph 2:{{$element['phone_number2']}}
                          </td>
                          <td>{{$element['email']}}</td>
                          <td>{{$element['verified']}}</td>
                          <td>
                            <form method="POST" action="{{route('show.rider.details')}}">
                              @csrf
                              <input type="hidden" name="rider" value="{{$element['id']}}">
                              <button type="submit" class="btn btn-primary">Details</button>
                            </form>
                          </td>
                          <td>
                          </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
                
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(".mDataTable").DataTable();
    </script>
@stop