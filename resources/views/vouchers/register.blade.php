@extends('layouts.app')
@section('title', 'Voucher Registration')
@section('content')
<section class="content-header">
    <h1>
        Register
        <small>Voucher</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('vouchers.index') }}"> Voucher</a></li>
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
                    <h3 class="box-title" style="float: left;">Voucher Details</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- form start -->
                <form action="{{route('vouchers.store')}}" method="post" class="form-horizontal" autocomplete="off">
                    <div class="box-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="transaction_type_debit" class="control-label"><b style="color: red;">* </b> Receipt : </label>
                                        <div class="input-group" title="Debit">
                                            <span class="input-group-addon">
                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_debit" value="1" {{ empty(old('transaction_type')) || old('transaction_type') == '1' ? 'checked' : ''}} tabindex="1">
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
                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_credit" value="2" {{ old('transaction_type') == '2' ? 'checked' : ''}} tabindex="2">
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
                                        <label for="account_id" class="control-label"><b style="color: red;">* </b> <b id="account_label">{{ (empty(old('transaction_type')) || old('transaction_type') == 1) ? "Giver " : "Reciever " }}</b> Account : </label>
                                        {{-- adding account select component --}}
                                        @component('components.selects.accounts', ['selectedAccountId' => old('account_id'), 'cashAccountFlag' => false, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6">
                                        <label for="transaction_date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                        <input type="text" class="form-control decimal_number_only datepicker_reg" name="transaction_date" id="transaction_date" placeholder="Transaction date" value="{{ old('transaction_date') }}" tabindex="4">
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
                                            <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="5"></textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount" class="control-label"><b style="color: red;">* </b> Amount : </label>
                                        <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Transaction amount" value="{{ old('amount') }}" maxlength="6" tabindex="6">
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
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7">Submit</button>
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
