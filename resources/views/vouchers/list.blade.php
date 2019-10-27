@extends('layouts.app')
@section('title', 'Voucher List')
@section('content')
<section class="content-header">
    <h1>
        Voucher
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('vouchers.index') }}"> Voucher</a></li>
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
                    <form action="{{ route('vouchers.index') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="from_date" class="control-label">From Date : </label>
                                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ !empty(old('from_date')) ? old('from_date') : $params['from_date']['paramValue'] }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-4">
                                        <label for="to_date" class="control-label">To Date : </label>
                                        <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ !empty(old('to_date')) ? old('to_date') : $params['to_date']['paramValue'] }}" tabindex="2">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label"><b style="color: red;">* </b> Voucher Type : </label>
                                        <select class="form-control select2" name="transaction_type" id="transaction_type" tabindex="10" style="width: 100%;">
                                            <option value="" {{ empty(old('transaction_type')) ? 'selected' : '' }}>Select voucher type</option>
                                            <option value="1" {{ (old('transaction_type') == 1) ? 'selected' : '' }}>Reciept</option>
                                            <option value="2" {{ (old('transaction_type') == 2) ? 'selected' : '' }}>Payment</option>
                                        </select>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_type'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="account_id" class="control-label">Account : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => $params['account_id']['paramValue'], 'cashAccountFlag' => false, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 5])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-4">
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
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="7">Clear</button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="8"><i class="fa fa-search"></i> Search</button>
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
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 15%;">Account</th>
                                        <th style="width: 10%;">Voucher Type</th>
                                        <th style="width: 26%;">Description</th>
                                        <th style="width: 12%;">Cash Recieved</th>
                                        <th style="width: 12%;">Cash Paid</th>
                                        <th style="width: 10%;" class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($vouchers))
                                        @foreach($vouchers as $index => $voucher)
                                            <tr>
                                                <td>{{ $index + $vouchers->firstItem() }}</td>
                                                <td>{{ $voucher->transaction->transaction_date->format('d-m-Y') }}</td>
                                                @if($voucher->transaction_type == 1)
                                                    <td>{{ $voucher->transaction->creditAccount->account_name }}</td>
                                                    <td>Receipt</td>
                                                    <td>{{ $voucher->description }}</td>
                                                    <td>{{ $voucher->amount }}</td>
                                                    <td></td>
                                                @else
                                                    <td>{{ $voucher->transaction->debitAccount->account_name }}</td>
                                                    <td>Payment</td>
                                                    <td>{{ $voucher->description }}</td>
                                                    <td></td>
                                                    <td>{{ $voucher->amount }}</td>
                                                @endif
                                                <td class="no-print">
                                                    <a href="{{ route('vouchers.edit', $voucher->id) }}" style="float: left;">
                                                        <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                                    </a>
                                                    <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="post" class="form-horizontal">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button type="button" class="btn btn-danger delete_button"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(Request::get('page') == $vouchers->lastPage() || $vouchers->lastPage() == 1)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><b>Total Amount</b></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><b>{{ $totalDebitAmount }}</b></td>
                                                <td class="text-red"><b>{{ $totalCreditAmount }}</b></td>
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
                            @if(!empty($vouchers))
                                <div>
                                    Showing {{ $vouchers->firstItem(). " - ". $vouchers->lastItem(). " of ". $vouchers->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $vouchers->appends(Request::all())->links() }}
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
