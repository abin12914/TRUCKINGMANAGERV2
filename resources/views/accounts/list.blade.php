@extends('layouts.app')
@section('title', 'Account List')
@section('content')
<section class="content-header">
    <h1>
        Account
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('accounts.index') }}"> Accounts</a></li>
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
                    <form action="{{ route('accounts.index') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="search_by_name" class="control-label">Name/Account Name : </label>
                                        <input type="text" class="form-control" name="search_by_name" id="search_by_name" value="{{ old('search_by_name', $params['search_by_name']['paramValue']) }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="relation_type" class="control-label">Relation : </label>
                                        @component('components.selects.account-relation', ['registrationFlag' => false, 'selectedRelation' => old('relation_type', $params['relation_type']['paramValue']), 'selectName' => 'relation_type', 'tabindex' => 2])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="account_id" class="control-label">Account : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id', $params['account_id']['paramValue']), 'cashAccountFlag' => false, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                        <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                        {{-- adding no of records text component --}}
                                        @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 4])
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
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="6">Clear</button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="5"><i class="fa fa-search"></i> Search</button>
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
                    @if(!empty($params['search_by_name']['paramValue']) || !empty($params['relation_type']['paramValue']) || !empty($params['account_id']['paramValue']))
                        <b>Filters applied!</b>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Account Name</th>
                                        <th style="width: 10%;">Relation</th>
                                        <th style="width: 15%;">Name</th>
                                        <th style="width: 15%;">Phone</th>
                                        <th style="width: 15%;">Opening Credit</th>
                                        <th style="width: 15%;">Opening Debit</th>
                                        <th style="width: 10%;" class="no-print">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($accounts))
                                        @foreach($accounts as $index => $account)
                                            <tr>
                                                <td>{{ $index + $accounts->firstItem() }}</td>
                                                <td title="Inative/Suspended">
                                                    {{ $account->account_name }}
                                                    @if($account->status != 1)
                                                        &emsp;<i class="fa fa-exclamation-triangle text-orange no-print"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ (!empty($accountRelations) && !empty($accountRelations[$account->relation])) ? $accountRelations[$account->relation] : "Error!" }}
                                                </td>
                                                <td>{{ $account->name }}</td>
                                                <td>{{ $account->phone }}</td>
                                                <td>{{ $account->financial_status == 1 ? $account->opening_balance : "-" }}</td>
                                                <td>{{ $account->financial_status == 2 ? $account->opening_balance : "-" }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('accounts.show', $account->id) }}">
                                                        <button type="button" class="btn btn-info">Details</button>
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
                            @if(!empty($accounts))
                                <div>
                                    Showing {{ $accounts->firstItem(). " - ". $accounts->lastItem(). " of ". $accounts->total() }}
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $accounts->appends(Request::all())->links() }}
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
