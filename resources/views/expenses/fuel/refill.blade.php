@extends('layouts.app')
@section('title', 'Fuel Refill')
@section('content')
<section class="content-header">
    <h1>
        Fuel
        <small>Refill</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('expenses.index') }}"> Expense</a></li>
        <li class="active"> Fuel Refill</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Fuel Refill</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('expense.fuel.refill.action') }}" method="post" class="form-horizontal" autocomplete="off">
                    @csrf()
                    <input type="hidden" name="truck_id" value="{{ !empty($truck) ? $truck->id : null }}" />
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <label for="truck" class="control-label"><b style="color: red;">* </b> Truck : </label>
                                        <input type="text" class="form-control read-only" name="truck" id="truck" placeholder="Truck" value="{{ !empty($truck) ? $truck->reg_number : null }}" tabindex="-1" readonly>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <label for="service" class="control-label"><b style="color: red;">* </b> Service : </label>
                                        <input type="text" class="form-control read-only" name="service" id="service" placeholder="Service" value="Certificate Renewal" tabindex="-1" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="transaction_date" class="control-label"><b style="color: red;">* </b> Transaction Date : </label>
                                        <input type="text" class="form-control decimal_number_only datepicker_reg" name="transaction_date" id="transaction_date" placeholder="Transaction date" value="{{ old('transaction_date') }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="account_id" class="control-label"><b style="color: red;">* </b> Supplier : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id'), 'cashAccountFlag' => true, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 2])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="description" class="control-label"><b style="color: red;">* </b> Description: </label>
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="3">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="3"></textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="amount" class="control-label"><b style="color: red;">* </b> Bill Amount: </label>
                                        <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Bill amount" value="{{ old('amount') }}" maxlength="8" tabindex="4">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'amount'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="certificate_type" class="control-label"><b style="color: red;">* </b> Certificate: </label>
                                        <select class="form-control select2" name="certificate_type" id="certificate_type" tabindex="5" style="width: 100%;">
                                            <option value="">Select certificate</option>
                                            @foreach ($certificateTypes as $key => $certificate)
                                                @if($truck->isCertExpired($certificate) || $truck->isCertCritical($certificate))
                                                    <option value="{{ $certificate }}" {{ old('certificate_type') == $certificate ? 'selected' : '' }}>
                                                        {{ $key }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="updated_date" class="control-label"><b style="color: red;">* </b> Updated Validity Date : </label>
                                        <input type="text" class="form-control decimal_number_only datepicker_forward" name="updated_date" id="updated_date" placeholder="Insurance expires" value="{{ old('updated_date') }}" tabindex="6">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'updated_date'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="clearfix"> </div><br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-0"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="button" class="btn btn-{{ empty($expense) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="7">
                                    {{ empty($expense) ? 'Add' : 'Update' }}
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
