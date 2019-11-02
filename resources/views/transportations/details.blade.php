@extends('layouts.app')
@section('title', 'Transportation Details')
@section('content')
@php
    $employeeWage = $transportation->employeeWages->first();
@endphp
<section class="content-header">
    <h1>
        Transportation
        <small>Details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('transportations.index') }}"> Transportation</a></li>
        <li class="active"> Details</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                @if(!empty($transportation))
                    <div class="box-header with-border">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/default_truck.png" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">
                            {{ $transportation->source->name }} <i class="fa fa-arrow-right"></i> {{ $transportation->destination->name }} <b>:</b> {{ $transportation->no_of_trip }} Trip(s)
                        </h3>
                        <h6 class="widget-user-desc">
                            {{ $transportation->truck->reg_number }}
                        </h6>
                    </div>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                    </strong>
                                    <p class="text-muted multi-line">
                                        #{{ $transportation->id }}/{{ $transportation->transaction->id }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-calendar margin-r-5"></i> Date
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->transaction->transaction_date->format('d-m-Y') }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-truck margin-r-5"></i> Truck Number
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->truck->reg_number }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-user-o margin-r-5"></i> Contractor
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->transaction->debitAccount->account_name }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-map-marker margin-r-5"></i> Source
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->source->name }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-map-marker margin-r-5"></i> Destination
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->destination->name }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-barcode margin-r-5"></i> Material
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->material->name }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-tag margin-r-5"></i> Rent Type
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ !empty($rentTypes) && $rentTypes[$transportation->rent_type] ? $rentTypes[$transportation->rent_type] : 'Error!' }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-balance-scale margin-r-5"></i> Measurement & Rate
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->measurement }} x {{ $transportation->rent_rate }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-inr margin-r-5"></i> Trip Rent x No of trip
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $transportation->trip_rent }} x {{ $transportation->no_of_trip }} = {{ $transportation->total_rent }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-user margin-r-5"></i> Driver
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $employeeWage->employee->account->account_name }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-user margin-r-5"></i> Driver Wage x No of trip
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $employeeWage->wage_amount }} x {{ $employeeWage->no_of_trip }} = {{ $employeeWage->total_wage_amount }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="clearfix"> </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2 col-xs-2"></div>
                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <form action="{{ route('transportations.edit', $transportation->id) }}" method="get" class="form-horizontal">
                                            <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                        </form>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <form action="{{ route('transportations.destroy', $transportation->id) }}" method="post" class="form-horizontal">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="button" class="btn btn-danger btn-block btn-flat delete_button">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                @endif
            </div>
            <!-- /.widget-user -->
        </div>
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
</div>
@endsection
