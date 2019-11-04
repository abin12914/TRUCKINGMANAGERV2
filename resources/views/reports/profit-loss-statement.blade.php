@extends('layouts.app')
@section('title', 'Profit-Loss Statement')
@section('content')
<section class="content-header">
    <h1>
        Profit-Loss Statement
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li> Reports</li>
        <li class="active"> Profit-Loss Statement</li>
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
                    <form action="{{ route('reports.profit-loss-statement') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="from_date" class="control-label">From Date : </label>
                                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ old('from_date', $params['from_date']['paramValue']) }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="to_date" class="control-label">To Date : </label>
                                        <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ old('to_date', $params['to_date']['paramValue']) }}" tabindex="2">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="truck_id" class="control-label">Truck : </label>
                                        {{-- adding truck select component --}}
                                        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', $params['truck_id']['paramValue']), 'selectName' => 'truck_id', 'tabindex' => 4])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-2 col-xs-0"></div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="8">Clear</button>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7"><i class="fa fa-search"></i> Search</button>
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
                            <h6 class="text-center">Account Statement</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Truck</th>
                                        <th style="width: 10%;">Transportation Rent</th>
                                        <th style="width: 10%;">Sales</th>
                                        <th style="width: 10%;">Purchase</th>
                                        <th style="width: 10%;">Employee Wage</th>
                                        <th style="width: 10%;">Expense</th>
                                        <th style="width: 10%;">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($trucks))
                                        @foreach($trucks as $index => $truck)
                                            <tr>
                                                <td>#{{ $index }}</td>
                                                <td>{{ $truck->reg_number }}</td>
                                                <td>{{ $truck->transportations->transaction->sum() }}</td>
                                                <td>{{ $truck->sales->transactions->sum() }}</td>
                                                <td>{{ $truck->purchases->transactions->sum() }}</td>
                                                <td>{{ $truck->employeeWages->transactions->sum() }}</td>
                                                <td>{{ $truck->expenses->transactions->sum() }}</td>
                                                <td>{{ $index }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
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
</section>
<!-- /.content -->
@endsection
