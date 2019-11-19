@extends('layouts.app')
@section('title', 'Credit Statement')
@section('content')
<section class="content-header">
    <h1>
        Credit Statement
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li> Reports</li>
        <li class="active"> Credit Statement</li>
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
                    <form action="{{ route('reports.credit-statement') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0"></div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                        <label for="account_id" class="control-label">Account Relation : </label>
                                        {{-- adding account relation select component --}}
                                        @component('components.selects.account-relation', ['registrationFlag' => false, 'selectedRelation' => old('relation_type', $params['relation_type']['paramValue']), 'selectName' => 'relation_type', 'tabindex' => 2])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                        <label for="to_date" class="control-label">Up To Closing Date : </label>
                                        <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ old('to_date', $params['to_date']['paramValue']) }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
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
                            <h6 class="text-center">Credit Statement up to {{ !empty($params['to_date']['paramValue']) ? $params['to_date']['paramValue'] : (\Carbon\Carbon::now()->format('d-m-Y H:i a')) }}</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 40%;">Account</th>
                                        <th style="width: 20%;">To Get</th>
                                        <th style="width: 20%;">To Pay</th>
                                        <th class="no-print" style="width: 15%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($accounts))
                                        @foreach($accounts as $index => $account)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $account->account_name }}</td>
                                                @if($account->creditAmount > 0)
                                                    <td>{{ $account->creditAmount }}</td>
                                                    <td>-</td>
                                                @else
                                                    <td>-</td>
                                                    <td>{{ $account->creditAmount * (-1) }}</td>
                                                @endif
                                                <td class="no-print">
                                                    <a href="{{ route('reports.account-statement', ['account_id' => $account->id]) }}">
                                                        <button type="button" class="btn btn-info">Account Statement</button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-red">{{ $totalDebit }}</td>
                                        <td class="text-red">{{ $totalCredit }}</td>
                                        <td class="no-print"></td>
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
</section>
<!-- /.content -->
@endsection
