@extends('layouts.app')
@section('title', 'Employee Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Edit
            <small>Employee</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('employee.index') }}"> Employee</a></li>
            <li class="active"> Edit</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(!empty($employee) && !empty($employee->id))
            <!-- Main row -->
            <div class="row no-print">
                <div class="col-md-12">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="float: left;">Employee Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form action="{{ route('employee.update', $employee->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                                @csrf()
                                @method('PUT')
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name', $employee->account->name) }}" tabindex="1" maxlength="100">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone', $employee->account->phone) }}" tabindex="2" maxlength="13" minlength="10">
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
                                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200">{{ $employee->account->address }}</textarea>
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
                                                    <select class="form-control select2" name="employee_type" id="employee_type" tabindex="4">
                                                        <option value="" {{ empty(old('employee_type')) ? 'selected' : '' }}>Select wage type</option>
                                                        <option value="1" {{ !empty(old('employee_type')) ? (old('employee_type') == 1 ? 'selected' : '') : ($employee->employee_type == 1 ? 'selected' : '') }}>Office Staff</option>
                                                        <option value="2" {{ !empty(old('employee_type')) ? (old('employee_type') == 2 ? 'selected' : '') : ($employee->employee_type == 2 ? 'selected' : '') }}>Machine Operator</option>
                                                    </select>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'employee_type'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="wage" class="col-md-3 control-label"><b style="color: red;">* </b> Wage : </label>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" name="wage" class="form-control decimal_number_only" id="wage" placeholder="Monthly salary/wage" value="{{ old('wage', $employee->wage) }}" tabindex="5">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'wage'])
                                                            @endcomponent
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select class="form-control select2" name="wage_type" id="wage_type" tabindex="6">
                                                                <option value="" {{ empty(old('wage_type')) ? 'selected' : '' }}>Select wage type</option>
                                                                @if(!empty($wageTypes))
                                                                    @foreach($wageTypes as $key => $wageType)
                                                                        <option value="{{ $key }}" {{ old('wage_type', $employee->wage_type) == $key ? 'selected' : '' }}>{{ $wageType }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
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
                                                    <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name', $employee->account->account_name) }}"  tabindex="7" maxlength="100">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'account_name'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                                <div class="col-md-9">
                                                    {{-- adding financial_status select component --}}
                                                    <select class="form-control select2" name="financial_status" id="financial_status" tabindex="8" style="width: 100%;">
                                                        <option value="0" {{ (old('financial_status', $employee->account->financial_status) == '0') ? 'selected' : '' }}>None (No pending transactions)</option>
                                                        <option value="2" {{ (old('financial_status', $employee->account->financial_status) == '2') ? 'selected' : '' }}>Debitor (Account holder owe to the company)</option>
                                                        <option value="1" {{ (old('financial_status', $employee->account->financial_status) == '1') ? 'selected' : '' }}>Creditor (Company owe to the account holder)</option>
                                                    </select>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'financial_status'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                                <div class="col-md-9">
                                                    {{-- adding opening_balance text component --}}
                                                    <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance', $employee->account->opening_balance) }}" {{ old('financial_status', $employee->account->opening_balance) == '0' ? 'readonly' : '' }} tabindex="9" maxlength="9">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'opening_balance'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div><br>
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                                        </div>
                                        {{-- <div class="col-md-1"></div> --}}
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary btn-block btn-flat update_button" tabindex="10">Submit</button>
                                        </div>
                                        <!-- /.col -->
                                    </div><br>
                                </div>
                            </form>
                        </div>
                        <!-- /.box primary -->
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        @else
            <h1 class="text-red">Selected Employee Record Not Found! <i class="fa fa-question text-blue" title="Selected employee record may be deleted or not available to you."></i></h1>
        @endif
    </section>
    <!-- /.content -->
</div>
@endsection