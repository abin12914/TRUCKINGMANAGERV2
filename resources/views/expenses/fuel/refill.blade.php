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
                    <input type="hidden" name="truck_reg_number" id="truck_reg_number" value="{{ !empty($truck) ? $truck->reg_number : null }}" />
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <label for="service" class="control-label"><b style="color: red;">* </b> Service : </label>
                                        <input type="text" class="form-control read-only" name="service" id="service" placeholder="Service" value="Fuel Refill" tabindex="-1" readonly>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="transaction_date" class="control-label"><b style="color: red;">* </b> Transaction Date : </label>
                                        <input type="text" class="form-control decimal_number_only datepicker_reg" name="transaction_date" id="transaction_date" placeholder="Transaction date" value="{{ old('transaction_date') }}" tabindex="1">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_date'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="truck_id" class="control-label"><b style="color: red;">* </b> Truck : </label>
                                        {{-- adding trucks select component --}}
                                        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', !empty($expense) ? $expense->truck_id : null), 'selectName' => 'truck_id', 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
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
                                        <label for="odometer_reading" class="control-label"><b style="color: red;">* </b> Odometer Reading: </label>
                                        <input type="text" class="form-control decimal_number_only" name="odometer_reading" id="odometer_reading" placeholder="Odometer Reading" value="{{ old('odometer_reading') }}" maxlength="15" tabindex="3">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'odometer_reading'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="last_odometer_reading" class="control-label"><b style="color: red;">* </b> Last Reading: </label>
                                        <input type="text" class="form-control no_edit" name="last_odometer_reading" id="last_odometer_reading" placeholder="Last Odometer Reading" value="{{ old('last_odometer_reading') }}" maxlength="15" tabindex="-1" readonly>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'last_odometer_reading'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                        <label for="fuel_quantity" class="control-label"><b style="color: red;">* </b> Fuel Quantity : </label>
                                        <input type="text" class="form-control decimal_number_only" name="fuel_quantity" id="fuel_quantity" placeholder="Fuel Quantity" value="{{ old('fuel_quantity') }}" tabindex="4">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'updated_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                        <label for="fuel_rate" class="control-label"><b style="color: red;">* </b> Rate : </label>
                                        <input type="text" class="form-control decimal_number_only" name="fuel_rate" id="fuel_rate" placeholder="Fuel Rate" value="{{ old('fuel_rate') }}" tabindex="-1" disabled>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'updated_date'])
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="amount" class="control-label"><b style="color: red;">* </b> Bill Amount: </label>
                                        <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Bill amount" value="{{ old('amount') }}" maxlength="8" tabindex="5">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'amount'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="description" class="control-label"><b style="color: red;">* </b> Description: </label>
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="6">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="6"></textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
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
@section('scripts')
    <script src="/js/registrations/fuelRefillRegistration.min.js"></script>
    <script src="/js/registrations/expenseRegistration.js"></script>
@endsection
