@extends('layouts.app')
@section('title', 'Truck List')
@section('content')
<section class="content-header">
    <h1>
        Truck
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Truck List</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {{-- page header for printers --}}
                @include('sections.print-head')
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-center">Trucks List</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Register Number</th>
                                        <th style="width: 20%;">Description</th>
                                        <th style="width: 15%;">Truck Type</th>
                                        <th style="width: 15%;">Volume</th>
                                        <th style="width: 15%;">Body Type</th>
                                        <th style="width: 15%;" class="no-print">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($trucks))
                                        @foreach($trucks as $index => $truck)
                                            <tr>
                                                <td>{{ $index + $trucks->firstItem() }}</td>
                                                <td>{{ $truck->reg_number }}</td>
                                                <td>{{ $truck->description }}</td>
                                                <td>{{ $truck->truckType->name }}</td>
                                                <td>{{ $truck->volume }} cft</td>
                                                <td>{{ (!empty($truckBodyTypes) && !empty($truckBodyTypes[$truck->truck_type_id])) ? $truckBodyTypes[$truck->truck_type_id] : "Error" }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('trucks.show', ['id' => $truck->id]) }}">
                                                        <button type="button" class="btn btn-info"> Details</button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($trucks))
                                <div>
                                    Showing {{ $trucks->firstItem(). " - ". $trucks->lastItem(). " of ". $trucks->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $trucks->appends(Request::all())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.boxy -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection