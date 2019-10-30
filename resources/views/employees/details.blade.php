@extends('layouts.app')
@section('title', 'Employee Details')
@section('content')
<section class="content-header">
    <h1>
        Employee
        <small>Details</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('employees.index') }}"> Employee</a></li>
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
                @if(!empty($employee))
                    <div class="box-header with-border">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/default_account.png" alt="Employee Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">{{ $employee->account->name }}</h3>
                        <h5 class="widget-user-desc">Employee</h5>
                    </div>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                    </strong>
                                    <p class="text-muted multi-line">
                                        #{{ $employee->id. "/". $employee->account_id }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-user-o margin-r-5"></i> Name
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $employee->account->name }}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-phone margin-r-5"></i> Address & Phone
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ (!empty($employee->account->address) ? ($employee->account->address. " - ") : ""). $employee->account->phone }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-inr margin-r-5"></i> Wage
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $employee->wage_value ." - ". (!empty($wageTypes) && !empty($wageTypes[$employee->wage_type]) ? $wageTypes[$employee->wage_type] : 'error')}}
                                    </p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-book margin-r-5"></i> Account Name
                                    </strong>
                                    <p class="text-muted multi-line">
                                        {{ $employee->account->account_name }}
                                    </p>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <strong>
                                        <i class="fa fa-calculator margin-r-5"></i> Opening Balance
                                    </strong>
                                    <p class="text-muted multi-line">
                                        @if($employee->account->financial_status == 1)
                                            Creditor -
                                        @elseif($employee->account->financial_status == 2)
                                            Debitor -
                                        @endif
                                        {{ $employee->account->opening_balance }}
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
                                    <form action="{{ route('employees.edit', $employee->id) }}" method="get" class="form-horizontal">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                    </form>
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
@endsection
