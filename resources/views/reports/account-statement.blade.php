@extends('layouts.app')
@section('title', 'Account Statement')
@section('content')
<section class="content-header">
    <h1>
        Account Statement
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li> Reports</li>
        <li class="active"> Account Statement</li>
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
                    <form action="{{ route('reports.account-statement') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="from_date" class="control-label">From Date : </label>
                                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ old('from_date', $params['from_date']['paramValue']) }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="to_date" class="control-label">To Date : </label>
                                        <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ old('to_date', $params['to_date']['paramValue']) }}" tabindex="2">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="account_id" class="control-label">Account : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id', $params['account_id']['paramValue']), 'cashAccountFlag' => true, 'selectName' => 'account_id', 'activeFlag' => true, 'tabindex' => 4])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
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
                            <h3>
                                Account statement of {{ $account->account_name. ($account->type == 3 ? (" : ". $account->phone) : null) }}
                                [ {{ ($params['from_date']['paramValue'] ?? 'start'). " - ". ($params['to_date']['paramValue'] ?? 'end') }} ]
                            </h3>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 10%;">Ref.No.</th>
                                        <th style="width: 45%;">Particulars</th>
                                        <th style="width: 15%;">Debit</th>
                                        <th style="width: 15%;">Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($transactions))
                                        @foreach($transactions as $index => $transaction)
                                            <tr>
                                                <td>{{ $index + $transactions->firstItem() }}</td>
                                                <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                                                <td>{{ $transaction->id }}</td>
                                                <td>{{ $transaction->particulars }}</td>
                                                @if($transaction->debit_account_id == $account->id)
                                                    <td>{{ $transaction->amount }}</td>
                                                    <td></td>
                                                @elseif($transaction->credit_account_id == $account->id)
                                                    <td></td>
                                                    <td>{{ $transaction->amount }}</td>
                                                @else
                                                    <td>0</td>
                                                    <td>0</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        @if(Request::get('page') == $transactions->lastPage() || $transactions->lastPage() == 1)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><b>Sub Total</b></td>
                                                <td class="text-red"><b>{{ $subTotalDebit }}</b></td>
                                                <td class="text-red"><b>{{ $subTotalCredit }}</b></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                @if($obDebit > $obCredit)
                                                    <td class="text-red"><strong> Old Balance To Get</strong></td>
                                                    <td class="text-red"><strong> {{ ($obDebit - $obCredit) }}</strong></td>
                                                    <td></td>
                                                @else
                                                    <td class="text-red"><strong> Old Balance To Pay</strong></td>
                                                    <td></td>
                                                    <td class="text-red"><strong> {{ ($obCredit - $obDebit) }}</strong></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-red"><strong> Total Amount</strong></td>
                                                @if($obDebit > $obCredit)
                                                    <td class="text-red"><strong> {{ ($subTotalDebit + ($obDebit - $obCredit)) }}</strong></td>
                                                    <td class="text-red"><strong> {{ $subTotalCredit }}</strong></td>
                                                @else
                                                    <td class="text-red"><strong> {{ $subTotalDebit }}</strong></td>
                                                    <td class="text-red"><strong> {{ ($subTotalCredit + ($obCredit - $obDebit)) }}</strong></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                @if(($subTotalDebit + $obDebit) > ($subTotalCredit + $obCredit))
                                                    <td class="text-red"><strong> Outstanding Balance To Get</strong></td>
                                                    <td></td>
                                                    <td class="text-red"><strong> {{ (($subTotalDebit + $obDebit) - ($subTotalCredit + $obCredit)) }}</strong></td>
                                                @else
                                                    <td class="text-red"><strong> Outstanding Balance To Pay</strong></td>
                                                    <td class="text-red"><strong> {{ (($subTotalCredit + $obCredit) - ($subTotalDebit + $obDebit)) }}</strong></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($transactions))
                                <div>
                                    Showing {{ $transactions->firstItem(). " - ". $transactions->lastItem(). " of ". $transactions->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $transactions->appends(Request::all())->links() }}
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
