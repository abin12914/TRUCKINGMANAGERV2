@extends('layouts.app')
@section('title', 'Truck '. (empty($truck) ? 'Add' : 'Edit'))
@section('content')
<section class="content-header">
    <h1>
        {{ empty($truck) ? 'Add' : 'Edit' }}
        <small>Truck</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('trucks.index') }}"> Truck</a></li>
        <li class="active"> {{ empty($truck) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Truck Details</h3>
                    <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($truck) ? route('trucks.store') : route('trucks.update', $truck->id) }}" method="post" class="form-horizontal" autocomplete="off">
                    @if(!empty($truck))
                        @method('PUT')
                    @endif
                    @csrf()
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        @if(!empty($truck))
                                            <label for="reg_number_state_code" class="control-label"><b style="color: red;">* </b> <i>Registration Number [Conatct Admin For Editing Registration Number]</i>: </label>
                                            <input type="text" name="reg_number" class="form-control text-center" id="reg_number" value="{{ !empty($truck) ? $truck->reg_number : null }}" readonly="">
                                        @else
                                            <label for="reg_number_state_code" class="control-label"><b style="color: red;">* </b> Registration Number : </label>
                                            <div class="row">
                                                <div class="col-md-2 col-xs-3 {{ !empty($errors->first('reg_number_state_code')) ? 'has-error' : '' }}" style="padding-right: 0px;">
                                                    <select class="form-control select2" name="reg_number_state_code" id="reg_number_state_code" tabindex="1" style="width: 100%;">
                                                        @if(!empty($stateCodes))
                                                            @foreach($stateCodes as $stateCode)
                                                                <option value="{{ $stateCode }}" {{ empty(old('reg_number_state_code')) ? ($stateCode == 'KL' ? "selected" : "") : (old('reg_number_state_code') == $stateCode ? 'selected' : '') }}>{{ $stateCode }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="" selected>Select state code</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-xs-2 {{ !empty($errors->first('reg_number_region_code')) ? 'has-error' : '' }}" style="padding-left: 0px; padding-right: 0px;">
                                                    <input type="text" name="reg_number_region_code" class="form-control number_only" id="reg_number_region_code" placeholder="" value="{{ old('reg_number_region_code') }}" tabindex="2" maxlength="2">
                                                </div>
                                                <div class="col-md-2 col-xs-2 {{ !empty($errors->first('reg_number_unique_alphabet')) ? 'has-error' : '' }}" style="padding-left: 0px; padding-right: 0px;">
                                                    <input type="text" name="reg_number_unique_alphabet" class="form-control alpha_only" id="reg_number_unique_alphabet" placeholder="" value="{{ old('reg_number_unique_alphabet') }}" tabindex="3" maxlength="2">
                                                </div>
                                                <div class="col-md-3 col-xs-2 {{ !empty($errors->first('reg_number_unique_digit')) ? 'has-error' : '' }}" style="padding-left: 0px; padding-right: 0px;">
                                                    <input type="text" name="reg_number_unique_digit" class="form-control number_only" id="reg_number_unique_digit" placeholder="" value="{{ old('reg_number_unique_digit') }}" tabindex="4" maxlength="4">
                                                </div>
                                                <div class="col-md-3 col-xs-3 {{ !empty($errors->first('reg_number')) ? 'has-error' : '' }}" style="padding-left: 0px;">
                                                    <input type="text" name="reg_number" class="form-control" id="reg_number" value="{{ old('reg_number') }}" readonly="">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="float: left;">Truck Details</h3>
                                </div><br>
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('ownership_status')) ? 'has-error' : '' }}">
                                            <label for="ownership_status" class="control-label">Ownership Status : </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    @if(!empty($truck))
                                                        <input type="checkbox" name="ownership_status_disabled" id="ownership_status" {{ $truck->ownership_status == 1 ? 'checked' : '' }} tabindex="-1" disabled>
                                                    @else
                                                        <input type="checkbox" name="ownership_status" id="ownership_status" value="1" {{ old('ownership_status') == 1 ? 'checked' : '' }} tabindex="5">
                                                    @endif
                                                </span>
                                                <label for="ownership_status" class="form-control">Company Own Truck</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label for="truck_type_id" class="control-label"><b style="color: red;">* </b> Truck Type : </label>
                                            <select class="form-control select2" name="truck_type_id" id="truck_type_id" tabindex="6" style="width: 100%;">
                                                <option value="" {{ empty(old('truck_type_id')) ? 'selected' : '' }}>Select truck type</option>
                                                @if(!empty($truckTypesCombo))
                                                    @foreach($truckTypesCombo as $truckType)
                                                        <option value="{{ $truckType->id }}" {{ (old('truck_type_id', !empty($truck) ? $truck->truck_type_id : null) == $truckType->id) ? 'selected' : '' }}>
                                                            {{ $truckType->name }} - {{ $truckType->generic_quantity }} cubic unit class
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'truck_type_id'])
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('body_type')) ? 'has-error' : '' }}">
                                            <label for="body_type" class="control-label"><b style="color: red;">* </b> Truck Body Type : </label>
                                            <select class="form-control select2" name="body_type" id="body_type" tabindex="7" style="width: 100%;">
                                                <option value="" {{ empty(old('body_type')) ? 'selected' : '' }}>Select body type</option>
                                                @if(!empty($truckBodyTypes))
                                                    @foreach($truckBodyTypes as $key => $bodyType)
                                                        <option value="{{ $key }}" {{ (old('body_type', !empty($truck) ? $truck->body_type : null) == $key) ? 'selected' : '' }}>
                                                            {{ $bodyType }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'body_type'])
                                            @endcomponent
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <label for="volume" class="control-label"><b style="color: red;">* </b> Volume In Feet : </label>
                                            <input type="text" class="form-control number_only" name="volume" id="volume" placeholder="Volume in cubic feet" value="{{ old('volume', !empty($truck) ? $truck->volume : null) }}" tabindex="8" maxlength="9">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'volume'])
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                            <label for="description" class="control-label">Description : </label>
                                            @if(!empty(old('description')))
                                                <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="9">{{ old('description') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="9">{{ !empty($truck) ? $truck->description : null }}</textarea>
                                            @endif
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                            @endcomponent
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('insurance_upto')) ? 'has-error' : '' }}">
                                            <label for="insurance_upto" class="control-label"><b style="color: red;">* </b> Insurance Expires : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="insurance_upto" id="insurance_upto" placeholder="Insurance expires" value="{{ old('insurance_upto', !empty($truck) ? $truck->insurance_upto->format('d-m-Y') : null) }}" tabindex="10">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'insurance_upto'])
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('tax_upto')) ? 'has-error' : '' }}">
                                            <label for="tax_upto" class="control-label"><b style="color: red;">* </b> Road Tax Expires : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="tax_upto" id="tax_upto" placeholder="Road tax expires" value="{{ old('tax_upto', !empty($truck) ? $truck->tax_upto->format('d-m-Y') : null) }}" tabindex="11">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'tax_upto'])
                                            @endcomponent
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('fitness_upto')) ? 'has-error' : '' }}">
                                            <label for="fitness_upto" class="control-label"><b style="color: red;">* </b> Certificate of Fitness Expires : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="fitness_upto" id="fitness_upto" placeholder="Certificate of fitness expires" value="{{ old('fitness_upto', !empty($truck) ? $truck->fitness_upto->format('d-m-Y') : null) }}" tabindex="12">
                                            @if(!empty($errors->first('fitness_upto')))
                                                <p style="color: red;" >{{$errors->first('fitness_upto')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('permit_upto')) ? 'has-error' : '' }}">
                                            <label for="permit_upto" class="control-label"><b style="color: red;">* </b> Permit Expires : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="permit_upto" id="permit_upto" placeholder="Permit expires" value="{{ old('permit_upto', !empty($truck) ? $truck->permit_upto->format('d-m-Y') : null) }}" tabindex="13">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'permit_upto'])
                                            @endcomponent
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 {{ !empty($errors->first('pollution_upto')) ? 'has-error' : '' }}">
                                            <label for="pollution_upto" class="control-label"><b style="color: red;">* </b> Pollution Certificate Expires : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="pollution_upto" id="pollution_upto" placeholder="Poluution under control certificate expires" value="{{ old('pollution_upto', !empty($truck) ? $truck->pollution_upto->format('d-m-Y') : null) }}" tabindex="14">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'pollution_upto'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"> </div><br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-0"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="16">Clear</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="button" class="btn btn-{{ empty($truck) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="15">
                                    {{ empty($truck) ? 'Add' : 'Update' }}
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
    <script src="/js/registrations/truckRegistration.min.js"></script>
@endsection
