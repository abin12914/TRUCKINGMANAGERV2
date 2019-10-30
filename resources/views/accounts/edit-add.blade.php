@extends('layouts.app')
@section('title', 'Account '. empty($account) ? 'Add' : 'Edit')
@section('content')
<section class="content-header">
    <h1>
        {{ empty($account) ? 'Add' : 'Edit' }}
        <small>Account</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('accounts.index') }}"> Accounts</a></li>
        <li class="active">{{ empty($account) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Account Details</h3>
                    <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($account) ? route('accounts.store') : route('accounts.update', $account->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                    @if(!empty($account))
                        @method('PUT')
                    @endif
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="account_name" class="col-md-3 control-label"><b style="color: red;">* </b> Account Name : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name', !empty($account) ? $account->account_name : null) }}" tabindex="1" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'account_name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-md-3 control-label">Description : </label>
                                    <div class="col-md-9">
                                        @if(!empty(old('description')))
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ old('description') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ !empty($account) ? $account->description : null }}</textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="float: left;">Personal Details</h3>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Account holder name" value="{{ old('name', !empty($account) ? $account->name : null) }}" tabindex="3" maxlength="100">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                    <div class="col-md-9">
                                        <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone', !empty($account) ? $account->phone : null) }}" tabindex="4" maxlength="13" minlength="10">
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'phone'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-md-3 control-label">Address : </label>
                                    <div class="col-md-9">
                                        @if(!empty(old('address')))
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200">{{ old('address') }}</textarea>
                                        @else
                                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200">{{ !empty($account) ? $account->address : null }}</textarea>
                                        @endif
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="relation_type" class="col-md-3 control-label"><b style="color: red;">* </b> Primary Relation : </label>
                                    <div class="col-md-9">
                                        {{-- adding account select component --}}
                                        @component('components.selects.account-relation', ['registrationFlag' => true, 'selectedRelation' => old('relation_type', !empty($account) ? $account->relation : null), 'selectName' => 'relation_type', 'tabindex' => 6])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="float: left;">Financial Details</h3>
                                        <p>&nbsp&nbsp&nbsp</p>
                                </div>
                                <div class="form-group">
                                    <label for="financial_status" class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                    <div class="col-md-9">
                                        {{-- adding financial_status select component --}}
                                        @component('components.selects.financial_status', ['selectedStatus' => old('financial_status', !empty($account) ? $account->financial_status : null), 'tabindex' => 7])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'financial_status'])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="opening_balance" class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                    <div class="col-md-9">
                                        {{-- adding opening_balance text component --}}
                                        @component('components.texts.opening_balance', ['selectedValue' => old('opening_balance', !empty($account) ? $account->opening_balance : null), 'readOnly' => (old('financial_status', !empty($account) ? $account->financial_status : null) == '0'), 'tabindex' => 8])
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
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="button" class="btn btn-{{ empty($account) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="9">
                                    {{ empty($account) ? 'Add' : 'Update' }}
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
