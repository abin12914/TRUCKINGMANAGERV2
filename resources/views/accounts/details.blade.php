@extends('layouts.app')
@section('title', 'Account Details')
@section('content')
 <section class="content-header">
    <h1>
        Account
        <small>Details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('accounts.index') }}"> Accounts</a></li>
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
                @if(!empty($account))
                    <div class="box-header with-border">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/default_account.png" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{{ $account->account_name }} {{ $account->status != 1 ? '(Suspended Account)' : '' }}</h3>
                        <h6 class="widget-user-desc">
                            {{ (!empty($accountRelations) && !empty($accountRelations[$account->relation])) ? $accountRelations[$account->relation] : "Error" }}
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
                                        #{{ $account->id }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-book margin-r-5"></i> Account Name
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $account->account_name }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-user-o margin-r-5"></i> Name
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $account->name }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-file-text-o margin-r-5"></i> Description
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $account->description ?? "-" }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-phone margin-r-5"></i> Phone
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $account->phone ?? "-" }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-map-marker margin-r-5"></i> Address
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $account->address ?? "-" }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-link margin-r-5"></i> Relation
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ (!empty($accountRelations) && !empty($accountRelations[$account->relation])) ? $accountRelations[$account->relation] : "Error" }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-calculator margin-r-5"></i> Opening Balance
                                    </strong>
                                    <p class="text-muted multi-line">
                                        @if($account->financial_status == 1)
                                            Creditor -
                                        @elseif($account->financial_status == 2)
                                            Debitor -
                                        @endif
                                        {{ $account->opening_balance }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="clearfix"> </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-4 col-xs-3"></div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                    @if($account->relation == 1)
                                        <a href="{{ route('employees.show', $account->employee->id) }}">
                                            <button type="button" class="btn btn-info btn-block btn-flat">Employee Details</button>
                                        </a>
                                    @else
                                        <form action="{{ route('accounts.edit', $account->id) }}" method="get" class="form-horizontal">
                                            <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                @endif
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection
