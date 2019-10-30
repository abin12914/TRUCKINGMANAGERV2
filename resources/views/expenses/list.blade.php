@extends('layouts.app')
@section('title', 'Expense List')
@section('content')
<section class="content-header">
    <h1>
        Expense
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('expenses.index') }}"> Expense</a></li>
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
                    <form action="{{ route('expenses.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="service_id" class="control-label">Service/Expense : </label>
                                        {{-- adding services select component --}}
                                        @component('components.selects.services', ['selectedServiceId' => old('service_id', $params['service_id']['paramValue']), 'selectName' => 'service_id', 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'service_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="account_id" class="control-label">Supplier : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id', $params['account_id']['paramValue']), 'cashAccountFlag' => true, 'selectName' => 'account_id', 'activeFlag' => true, 'tabindex' => 4])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="truck_id" class="control-label">Truck : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', $params['truck_id']['paramValue']), 'selectName' => 'truck_id', 'tabindex' => 5])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                        {{-- adding no of records text component --}}
                                        @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 6])
                                        @endcomponent
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
                        <div class="col-md-12">
                            <h6 class="text-center">Expenses List</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 15%;">Service</th>
                                        <th style="width: 15%;">Supplier</th>
                                        <th style="width: 10%;">Truck</th>
                                        <th style="width: 25%;">Notes</th>
                                        <th style="width: 10%;">Amount</th>
                                        <th style="width: 10%;" class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($expenses))
                                        @foreach($expenses as $index => $expense)
                                            <tr>
                                                <td>{{ $index + $expenses->firstItem() }}</td>
                                                <td>{{ $expense->transaction->transaction_date->format('d-m-Y') }}</td>
                                                <td>{{ $expense->service->name }}</td>
                                                <td>{{ $expense->transaction->creditAccount->account_name }}</td>
                                                <td>{{ $expense->truck->reg_number }}</td>
                                                <td>{{ $expense->description }}</td>
                                                <td>{{ $expense->amount }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('expenses.edit', $expense->id) }}" style="float: left;">
                                                        <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                                    </a>
                                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="post" class="form-horizontal">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button type="button" class="btn btn-danger delete_button"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(Request::get('page') == $expenses->lastPage() || $expenses->lastPage() == 1)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><b>Total Amount</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><b>{{ $totalExpense }}</b></td>
                                                <td class="no-print"></td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($expenses))
                                <div>
                                    Showing {{ $expenses->firstItem(). " - ". $expenses->lastItem(). " of ". $expenses->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $expenses->appends(Request::all())->links() }}
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
