@extends('layouts.app')
@section('title', 'Supply Transportations List - Customer Copy')
@section('content')
<section class="content-header">
    <h1>
        Supply Transportations - Customer Copy
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('supply.customer.copy') }}"> Supply Transportation - customer copy</a></li>
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
                    <form action="{{ route('supply.customer.copy') }}" method="get" class="form-horizontal" autocomplete="off">
                        {{-- supply filter form added --}}
                        @include('components.forms.filter.supply.supply-filter')
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
                <div class="box-header text-center">
                    @if(!empty($transportations->first()))
                        @if(!empty($params['from_date']['paramValue']))
                            <b>From : {{  $params['from_date']['paramValue'] }}</b>&emsp;
                        @endif
                        @if(!empty($params['to_date']['paramValue']))
                            <b>To : {{  $params['to_date']['paramValue'] }}</b>&emsp;
                        @endif
                        @if(!empty($params['contractor_account_id']['paramValue']))
                            <b>Customer : {{  $transportations->first()->transaction->debitAccount->account_name }}</b>&emsp;
                        @endif
                        @if(!empty($params['destination_id']['paramValue']))
                            <b>Destination : {{  $transportations->first()->destination->name }}</b>&emsp;
                        @endif
                    @endif
                    @if(empty($params['contractor_account_id']['paramValue']))
                        <h3 class="box-body no-print text-danger">
                            Customer selection is mandatory in customer copy. Please select a customer from above contractor list.<br />
                            If you like to print list without customer, use supply->list.
                        </h3>
                    @endif
                </div>
                <div class="box-body {{ (empty($params['contractor_account_id']['paramValue'])) ? 'no-print' : '' }}">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 12%;">Date</th>
                                        <th style="width: 18%;">Truck</th>
                                        <th style="width: 20%;">Destination</th>
                                        <th style="width: 15%;">Material</th>
                                        <th style="width: 10%;">No Of Trip</th>
                                        <th style="width: 20%;">Bill Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($transportations))
                                        @foreach($transportations as $index => $transportation)
                                            <tr>
                                                <td>{{ $index + $transportations->firstItem() }}</td>
                                                <td>{{ $transportation->transaction->transaction_date->format('d-m-Y') }}</td>
                                                <td>{{ $transportation->truck->reg_number }}</td>
                                                <td>{{ $transportation->destination->name }}</td>
                                                <td>{{ $transportation->material->name }}</td>
                                                <td>{{ $transportation->no_of_trip }}</td>
                                                <td>{{ $transportation->total_rent + $transportation->sale->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($transportations))
                                <div>
                                    Showing {{ $transportations->firstItem(). " - ". $transportations->lastItem(). " of ". $transportations->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $transportations->appends(Request::all())->links() }}
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
