@extends('layouts.app')
@section('title', 'Supply Details')
@section('content')
<section class="content-header">
    <h1>
        Supply
        <small>Details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('supply.index') }}"> Supply</a></li>
        <li class="active"> Details</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Transportation Details</h3>
                </div><br>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Truck</th>
                                        <th style="width: 20%;">Source</th>
                                        <th style="width: 20%;">Destination</th>
                                        <th style="width: 10%;">No. Of Trip</th>
                                        <th style="width: 10%;">Material</th>
                                        <th style="width: 30%;">Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $supplyTransportation->truck->reg_number }}</td>
                                        <td>{{ $supplyTransportation->source->name }}</td>
                                        <td>{{ $supplyTransportation->destination->name }}</td>
                                        <td>{{ $supplyTransportation->no_of_trip }}</td>
                                        <td>{{ $supplyTransportation->material->name }}</td>
                                        <td>
                                            @foreach ($supplyTransportation->employeeWages as $key => $employeeWage)
                                                {{ $employeeWage->employee->account->account_name }}[{{ $employeeWage->wage_amount }} x {{ $employeeWage->no_of_trip }} = {{ $employeeWage->total_wage_amount }}/-]<br />
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.boxy -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row (main row) -->

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <!-- /.box-body -->
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Rent Details</h3>
                </div><br>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Ref. No.</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 20%;">Contractor</th>
                                        <th style="width: 10%;">Rent type</th>
                                        <th style="width: 10%;">Measurement</th>
                                        <th style="width: 10%;">Rent rate</th>
                                        <th style="width: 10%;">Discount</th>
                                        <th style="width: 15%;">Total Rent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-muted">
                                            #{{ $supplyTransportation->id }}/{{ $supplyTransportation->transaction->id }}
                                        </td>
                                        <td>
                                            {{ $supplyTransportation->transaction->transaction_date->format('d-m-Y') }}
                                        </td>
                                        <td class="text-red">
                                            {{ $supplyTransportation->transaction->debitAccount->account_name }}
                                        </td>
                                        <td>
                                            {{ !empty($rentTypes) ? ($rentTypes[$supplyTransportation->rent_type] ?? 'Error') : 'Error' }}
                                        </td>
                                        <td>{{ $supplyTransportation->measurement }}</td>
                                        <td>{{ $supplyTransportation->rent_rate }}</td>
                                        <td>0</td>
                                        <td>
                                            {{ $supplyTransportation->trip_rent }} x {{ $supplyTransportation->no_of_trip }} = {{ $supplyTransportation->total_rent }}/-
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Purchase Details</h3>
                </div><br>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Ref. No.</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 20%;">Supplier</th>
                                        <th style="width: 10%;">Measure type</th>
                                        <th style="width: 10%;">Quantity</th>
                                        <th style="width: 10%;">Rate</th>
                                        <th style="width: 10%;">Discount</th>
                                        <th style="width: 15%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-muted">
                                            #{{ $supplyTransportation->purchase->id }}/{{ $supplyTransportation->purchase->transaction_id }}
                                        </td>
                                        <td>
                                            {{ $supplyTransportation->purchase->transaction->transaction_date->format('d-m-Y') }}
                                        </td>
                                        <td class="text-red">
                                            {{ $supplyTransportation->purchase->transaction->creditAccount->account_name }}
                                        </td>
                                        <td>
                                            {{ !empty($measureTypes) ? (!empty($measureTypes[$supplyTransportation->purchase->measure_type]) ?? "Error!") : 'Error' }}
                                        </td>
                                        <td>{{ $supplyTransportation->purchase->quantity }}</td>
                                        <td>{{ $supplyTransportation->purchase->rate }}</td>
                                        <td>{{ $supplyTransportation->purchase->discount }}</td>
                                        <td>
                                            {{ $supplyTransportation->purchase->purchase_trip_bill }} x {{ $supplyTransportation->purchase->no_of_trip }} = {{ $supplyTransportation->purchase->total_amount }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Sale Details</h3>
                </div><br>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Ref. No.</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 20%;">Customer</th>
                                        <th style="width: 10%;">Measure type</th>
                                        <th style="width: 10%;">Quantity</th>
                                        <th style="width: 10%;">Rate</th>
                                        <th style="width: 10%;">Discount</th>
                                        <th style="width: 15%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-muted">
                                            #{{ $supplyTransportation->sale->id }}/{{ $supplyTransportation->sale->transaction_id }}
                                        </td>
                                        <td>
                                            {{ $supplyTransportation->sale->transaction->transaction_date->format('d-m-Y') }}
                                        </td>
                                        <td class="text-red">
                                            {{ $supplyTransportation->sale->transaction->debitAccount->account_name }}
                                        </td>
                                        <td>
                                            {{ !empty($measureTypes) ? (!empty($measureTypes[$supplyTransportation->sale->measure_type]) ?? "Error!") : 'Error' }}
                                        </td>
                                        <td>{{ $supplyTransportation->sale->quantity }}</td>
                                        <td>{{ $supplyTransportation->sale->rate }}</td>
                                        <td>{{ $supplyTransportation->sale->discount }}</td>
                                        <td>
                                            {{ $supplyTransportation->sale->sale_trip_bill }} x {{ $supplyTransportation->sale->no_of_trip }} = {{ $supplyTransportation->sale->total_amount }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    <div class="row no-print">
        <div class="col-md-12 col-xs-12">
            <div class="clearfix"> </div><br>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-2"></div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <form action="{{ route('supply.edit', $supplyTransportation->id) }}" method="get" class="form-horizontal">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <form action="{{ route('supply.destroy', $supplyTransportation->id) }}" method="post" class="form-horizontal">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-danger btn-block btn-flat delete_button">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
