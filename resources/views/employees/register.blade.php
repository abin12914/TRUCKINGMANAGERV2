@extends('layouts.app')
@section('title', 'Employee Registration')
@section('content')
<section class="content-header">
    <h1>
        Register
        <small>Employee</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('employees.index') }}"> Employee</a></li>
        <li class="active"> Registration</li>
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
                <form action="{{route('employees.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="2" maxlength="13" minlength="10">
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
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200"></textarea>
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
                                            <option value="1" {{ !empty(old('employee_type')) && old('employee_type') == 1 ? 'selected' : '' }}>Office Staff</option>
                                            <option value="2" {{ !empty(old('employee_type')) && old('employee_type') == 2 ? 'selected' : '' }}>Machine Operator</option>
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
                                                <input type="text" name="wage" class="form-control decimal_number_only" id="wage" placeholder="Salary/wage" value="{{ old('wage') }}" tabindex="5">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'wage'])
                                                @endcomponent
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2" name="wage_type" id="wage_type" tabindex="6">
                                                    <option value="" {{ empty(old('wage_type')) ? 'selected' : '' }}>Select wage type</option>
                                                    @if(!empty($wageTypes))
                                                        @foreach($wageTypes as $key => $wageType)
                                                            <option value="{{ $key }}" {{ old('wage_type') == $key ? 'selected' : '' }}>{{ $wageType }}</option>
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
                                        <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name') }}"  tabindex="7" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                    <div class="col-md-9">
                                        {{-- adding financial_status select component --}}
                                        @component('components.selects.financial_status', ['tabindex' => 8])
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
                                        @component('components.texts.opening_balance', ['tabindex' => 9])
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
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                            </div>
                            {{-- <div class="col-md-1"></div> --}}
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="10">Submit</button>
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