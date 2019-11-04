@extends('layouts.app')
@section('title', 'Voucher '. empty($voucher) ? 'Add' : 'Edit')
@section('content')
<section class="content-header">
    <h1>
        {{ empty($voucher) ? 'Add' : 'Edit' }}
        <small>Voucher</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('vouchers.index') }}"> Voucher</a></li>
        <li class="active"> {{ empty($voucher) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Voucher Details</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- form start -->
                <form action="{{ empty($voucher) ? route('vouchers.store') : route('vouchers.update', $voucher->id) }}" method="post" class="form-horizontal" autocomplete="off">
                    @if(!empty($voucher))
                        @method('PUT')
                    @endif
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="transaction_type_debit" class="control-label"><b style="color: red;">* </b> Receipt : </label>
                                        <div class="input-group" title="Debit">
                                            <span class="input-group-addon">
                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_debit" value="1" {{ old('transaction_type', !empty($voucher) ? $voucher->transaction_type : null) == '1' ? 'checked' : '' }} tabindex="1">
                                            </span>
                                            <label for="transaction_type_debit" class="form-control">Receipt [Cash Received]</label>
                                        </div>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_type'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6">
                                        <label for="transaction_type_credit" class="control-label"><b style="color: red;">* </b> Payment : </label>
                                        <div class="input-group" title="Credit">
                                            <span class="input-group-addon">
                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_credit" value="2" {{ old('transaction_type', !empty($voucher) ? $voucher->transaction_type : null) == '2' ? 'checked' : '' }} tabindex="2">
                                            </span>
                                            <label for="transaction_type_credit" class="form-control">Payment [Cash Paid]</label>
                                        </div>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_type'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="account_id" class="control-label"><b style="color: red;">* </b> <b id="account_label">{{ (old('transaction_type', !empty($voucher) ? $voucher->transaction_type : null) == 1) ? "Giver " : "Reciever " }}</b> Account : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id', !empty($voucher) ? ($voucher->transaction_type == 2 ? $voucher->transaction->debit_account_id : $voucher->transaction->credit_account_id) : null), 'cashAccountFlag' => false, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6">
                                        <label for="transaction_date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                        <input type="text" class="form-control decimal_number_only {{ empty($voucher) ? 'datepicker_reg' : 'datepicker' }}" name="transaction_date" id="transaction_date" placeholder="Transaction date" value="{{ old('transaction_date', !empty($voucher) ? $voucher->transaction->transaction_date->format('d-m-Y') : null) }}" tabindex="4">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'transaction_date'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="description" class="control-label"><b style="color: red;">* </b>Description : </label>
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="5">{{ !empty($voucher) ? (preg_replace('/\[.*?\]/', '', $voucher->transaction->particulars)) : null }}</textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount" class="control-label"><b style="color: red;">* </b> Amount : </label>
                                        <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Transaction amount" value="{{ old('amount', !empty($voucher) ? $voucher->amount : null) }}" maxlength="6" tabindex="6">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'amount'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="clearfix"> </div><br>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-{{ empty($voucher) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="7">
                                    {{ empty($voucher) ? 'Add' : 'Update' }}
                                </button>
                            </div>
                            <!-- /.col -->
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
@section('scripts')
    <script src="/js/registrations/voucherRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection
