@extends('layouts.app')
@section('title', 'Employee List')
@section('content')
 <section class="content-header">
    <h1>
        Employee
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a> Employee</a></li>
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
                    <form action="{{ route('employees.index') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="employee_id" class="control-label">Employee : </label>
                                        {{-- adding employee select component --}}
                                        @component('components.selects.employees', ['selectedEmployeeId' => $params['employee_id']['paramValue'], 'selectName' => 'employee_id', 'tabindex' => 2])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'employee_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="employee_type" class="control-label">Employee Type : </label>
                                        {{-- adding account employee type component --}}
                                        @component('components.selects.employee-type', ['selectedType' => old('employee_type'. $params['employee_type']['paramValue']), 'selectName' => 'employee_type', 'tabindex' => 1])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'employee_type'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                        <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                        {{-- adding no of records text component --}}
                                        @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 3])
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
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="5">Clear</button>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
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
                    @if(!empty($params['employee_type']['paramValue']) || !empty($params['employee_id']['paramValue']))
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
                                        <th style="width: 20%;">Employee Name</th>
                                        <th style="width: 20%;">Employee Type</th>
                                        <th style="width: 15%;">Wage</th>
                                        <th style="width: 25%;">Account Name</th>
                                        <th style="width: 15%;" class="no-print">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($employees))
                                        @foreach($employees as $index => $employee)
                                            <tr>
                                                <td>{{ $index + $employees->firstItem() }}</td>
                                                <td>{{ $employee->account->name }}</td>
                                                <td>{{ $employee->employee_type == 1 ? "Office Staff" : "Machine Operator" }}</td>
                                                <td>{{ $employee->wage_value }} - {{ !empty($wageTypes[$employee->wage_type]) ? $wageTypes[$employee->wage_type] : "Error!" }}</td>
                                                <td>{{ $employee->account->account_name }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('employees.show', ['id' => $employee->id]) }}">
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
                            @if(!empty($employees))
                                <div>
                                    Showing {{ $employees->firstItem(). " - ". $employees->lastItem(). " of ". $employees->total() }}
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $employees->appends(Request::all())->links() }}
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
