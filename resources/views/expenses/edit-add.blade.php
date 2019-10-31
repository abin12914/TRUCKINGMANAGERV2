@extends('layouts.app')
@section('title', 'Expense '. empty($expense) ? 'Add' : 'Edit')
@section('content')
<section class="content-header">
    <h1>
        {{ empty($expense) ? 'Add' : 'Edit' }}
        <small>Expense</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('expenses.index') }}"> Expense</a></li>
        <li class="active"> {{ empty($expense) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Expense Details</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($expense) ? route('expenses.store') : route('expenses.update', $expense->id) }}" method="post" class="form-horizontal" autocomplete="off">
                    @if(!empty($expense))
                        @method('PUT')
                    @endif
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="transaction_date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                            <input type="text" class="form-control decimal_number_only {{ empty($expense) ? 'datepicker_reg' : 'datepicker' }}" name="transaction_date" id="transaction_date" placeholder="Transaction date" value="{{ old('transaction_date', !empty($expense) ? $expense->transaction->transaction_date->format('d-m-Y') : null) }}" tabindex="1">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'transaction_date'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="account_id" class="control-label"><b style="color: red;">* </b> Supplier : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => old('account_id', !empty($expense) ? $expense->transaction->credit_account_id : null), 'cashAccountFlag' => true, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="truck_id" class="control-label"><b style="color: red;">* </b> Truck : </label>
                                            {{-- adding trucks select component --}}
                                            @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', !empty($expense) ? $expense->truck_id : null), 'selectName' => 'truck_id', 'tabindex' => 3])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="service_id" class="control-label"><b style="color: red;">* </b> Service : </label>
                                            {{-- adding services select component --}}
                                            @component('components.selects.services', ['selectedServiceId' => old('service_id', !empty($expense) ? $expense->service_id : null), 'selectName' => 'service_id', 'tabindex' => 4])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'service_id'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="description" class="control-label"><b style="color: red;">* </b> Description: </label>
                                            @if(!empty(old('description')))
                                                <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="5">{{ !empty($expense) ? $expense->description : null }}</textarea>
                                            @endif
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="amount" class="control-label"><b style="color: red;">* </b> Bill Amount: </label>
                                            <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Bill amount" value="{{ old('amount', !empty($expense) ? $expense->amount : null) }}" maxlength="8" tabindex="6">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'amount'])
                                            @endcomponent
                                        </div>
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
