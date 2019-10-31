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
        <li><a> Truck</a></li>
        <li class="active"> List</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row  no-print">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-header">
                    <form action="{{ route('trucks.index') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="employee_id" class="control-label">Truck : </label>
                                        {{-- adding truck select component --}}
                                        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', $params['truck_id']['paramValue']), 'selectName' => 'truck_id', 'tabindex' => 1])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="employee_type" class="control-label">Truck Type : </label>
                                        <select class="form-control select2" name="truck_type_id" id="truck_type_id" tabindex="6" style="width: 100%;">
                                            <option value="" {{ empty(old('truck_type_id')) ? 'selected' : '' }}>Select truck type</option>
                                            @if(!empty($truckTypesCombo))
                                                @foreach($truckTypesCombo as $truckType)
                                                    <option value="{{ $truckType->id }}" {{ (old('truck_type_id', !empty($truck) ? $truck->truck_type_id : null) == $truckType->id) ? 'selected' : '' }}>
                                                        {{ $truckType->name }} - {{ $truckType->generic_quantity }} cubic unit class
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'truck_type'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="ownership_status" class="control-label">Ownership : </label>
                                        <select class="form-control select2" name="ownership_status" id="ownership_status" tabindex="3" style="width: 100%;">
                                            <option value="" {{ empty(old('ownership_status')) ? 'selected' : '' }}>Select status</option>
                                            <option value="0" {{ old('ownership_status', $params['ownership_status']['paramValue']) == '0' ? 'selected' : '' }}>All trucks</option>
                                            <option value="1" {{ old('ownership_status', $params['ownership_status']['paramValue']) == '1' ? 'selected' : '' }}>Own trucks only</option>
                                        </select>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-2 col-xs-0"></div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="5">Clear</button>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.form end -->
                </div>
            </div>
        </div>
    </div>
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
                                        <th style="width: 10%;">Own</th>
                                        <th style="width: 20%;">Description</th>
                                        <th style="width: 10%;">Truck Type</th>
                                        <th style="width: 10%;">Volume</th>
                                        <th style="width: 15%;">Body Type</th>
                                        <th style="width: 15%;" class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($trucks))
                                        @foreach($trucks as $index => $truck)
                                            <tr>
                                                <td>{{ $index + $trucks->firstItem() }}</td>
                                                <td>{{ $truck->reg_number }}</td>
                                                <td>{{ $truck->ownership_status == 1 ? 'Yes' : 'No' }}</td>
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
