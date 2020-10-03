@extends('layouts.app')
@section('title', 'Supply Transportations List')
@section('content')
<section class="content-header">
    <h1>
        Supply Transportations
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('supply.index') }}"> Supply Transportation</a></li>
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
                    <form action="{{ route('supply.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                <div class="box-header no-print">
                    @foreach($params as $param)
                        @if(!empty($param['paramValue']))
                            <b>Filters applied!</b>
                            @break
                        @endif
                    @endforeach
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 10%;">Truck</th>
                                        <th style="width: 15%;">Contractor</th>
                                        <th style="width: 30%;">Source - Destination</th>
                                        <th style="width: 10%;" class="no-print">Material</th>
                                        <th style="width: 10%;">No Of Trip</th>
                                        <th style="width: 10%;" class="no-print">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($transportations))
                                        @foreach($transportations as $index => $transportation)
                                            <tr>
                                                <td>{{ $index + $transportations->firstItem() }}</td>
                                                <td>{{ $transportation->transaction->transaction_date->format('d-m-Y') }}</td>
                                                <td>{{ $transportation->truck->reg_number }}</td>
                                                <td>{{ $transportation->transaction->debitAccount->account_name }}</td>
                                                <td>{{ $transportation->source->name }} - {{ $transportation->destination->name }}</td>
                                                <td class="no-print">{{ $transportation->material->name }}</td>
                                                <td>{{ $transportation->no_of_trip }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('supply.show', ['id' => $transportation->id]) }}">
                                                        <button type="button" class="btn btn-default">Details</button>
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
