@extends('layouts.app')
@section('title', 'Employee '. (empty($employee) ? 'Add' : 'Edit'))
@section('content')
<section class="content-header">
    <h1>
        {{ empty($employee) ? 'Add' : 'Edit' }}
        <small>Employee</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('employees.index') }}"> Employee</a></li>
        <li class="active"> {{ empty($employee) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Employee Details</h3>
                    <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($employee) ? route('employees.store') : route('employees.update', $employee->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                    @if(!empty($employee))
                        @method('PUT')
                    @endif
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name', !empty($employee) ? $employee->account->name : null) }}" tabindex="1" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone', !empty($employee) ? $employee->account->phone : null) }}" tabindex="2" maxlength="13" minlength="10">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'phone'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-md-3 control-label">Address : </label>
                                    <div class="col-md-9">
                                        @if(!empty(old('address')))
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200">{{ old('address') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200">{{ !empty($employee) ? $employee->account->address : null }}</textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="float: left;">Wage Details</h3>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><b style="color: red;">* </b> Employee Type : </label>
                                    <div class="col-md-9">
                                        {{-- adding account employee type component --}}
                                        @component('components.selects.employee-type', ['selectedType' => old('employee_type', !empty($employee) ? $employee->employee_type : null), 'selectName' => 'employee_type', 'tabindex' => 4])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'employee_type'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wage_value" class="col-md-3 control-label"><b style="color: red;">* </b> Wage : </label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <input type="text" name="wage_value" class="form-control decimal_number_only" id="wage_value" placeholder="Salary/wage value" value="{{ old('wage_value', !empty($employee) ? $employee->wage_value : null) }}" tabindex="5">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'wage_value'])
                                                @endcomponent
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                {{-- adding wage type select component --}}
                                                @component('components.selects.wage-type', ['selectedType' => old('wage_type', !empty($employee) ? $employee->wage_type : null), 'selectName' => 'wage_type', 'tabindex' => 6])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'wage_type'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="float: left;">Account Details</h3>
                                </div>
                                <div class="form-group">
                                    <label for="account_name" class="col-md-3 control-label"><b style="color: red;">* </b> Account Name : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name', !empty($employee) ? $employee->account->account_name : null) }}"  tabindex="7" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                    <div class="col-md-9">
                                        {{-- adding financial_status select component --}}
                                        @component('components.selects.financial_status', ['selectedStatus' => old('financial_status', !empty($employee) ? $employee->account->financial_status : null), 'tabindex' => 8])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'financial_status'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                    <div class="col-md-9">
                                        {{-- adding opening_balance text component --}}
                                        @component('components.texts.opening_balance', ['selectedValue' => old('opening_balance', !empty($employee) ? $employee->account->opening_balance : null), 'readOnly' => (old('financial_status', !empty($employee) ? $employee->account->financial_status : null) == '0'), 'tabindex' => 9])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'opening_balance'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"> </div><br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-0"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                            </div>
                            {{-- <div class="col-md-1"></div> --}}
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="button" class="btn btn-{{ empty($employee) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="10">
                                    {{ empty($employee) ? 'Add' : 'Update' }}
                                </button>
                            </div>
                            <!-- /.col -->
                        </div><br>
                    </div>
                </form>
            </div>
            <!-- /.box primary -->
        </div>
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
