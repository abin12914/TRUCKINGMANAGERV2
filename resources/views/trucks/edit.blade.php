@extends('layouts.app')
@section('title', 'Truck Edit')
@section('content')
<section class="content-header">
    <h1>
        Edit
        <small>Truck</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('trucks.index') }}"> Truck</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    @if(!empty($truck) && !empty($truck->id))
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
                    <form action="{{route('trucks.update', $truck->id)}}" method="post" class="form-horizontal" autocomplete="off">
                        <div class="box-body">
                            @csrf()
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="reg_number_state_code" class="control-label"><b style="color: red;">* </b> <i>Registration Number [Conatct Admin For Editing Registration Number]</i>: </label>
                                            <input type="text" name="reg_number" class="form-control text-center" id="reg_number" value="{{ $truck->reg_number }}" readonly="">
                                        </div>
                                        @if(!empty($errors->first('reg_number')))
                                            <p style="color: red;" >{{$errors->first('reg_number')}}</p>
                                        @elseif(!empty($errors->first('reg_number_state_code')))
                                            <p style="color: red;" >{{$errors->first('reg_number_state_code')}}</p>
                                        @elseif(!empty($errors->first('reg_number_region_code')))
                                            <p style="color: red;" >{{$errors->first('reg_number_region_code')}}</p>
                                        @elseif(!empty($errors->first('reg_number_unique_alphabet')))
                                            <p style="color: red;" >{{$errors->first('reg_number_unique_alphabet')}}</p>
                                        @elseif(!empty($errors->first('reg_number_unique_digit')))
                                            <p style="color: red;" >{{$errors->first('reg_number_unique_digit')}}</p>
                                        @endif
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
                                            <div class="col-md-6 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                <label for="ownership_status" class="control-label">Ownership Status : </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <input type="checkbox" name="ownership_status" id="ownership_status" value="1" {{ old('ownership_status', $truck->ownership_status) == 1 ? 'checked' : '' }} tabindex="4">
                                                    </span>
                                                    <label for="ownership_status" class="form-control">Company Own Truck</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="truck_type_id" class="control-label"><b style="color: red;">* </b> Truck Type : </label>
                                                <select class="form-control select2" name="truck_type_id" id="truck_type_id" tabindex="6" style="width: 100%;">
                                                    <option value="" {{ empty(old('truck_type_id')) ? 'selected' : '' }}>Select truck type</option>
                                                    @if(!empty($truckTypesCombo))
                                                        @foreach($truckTypesCombo as $truckType)
                                                            <option value="{{ $truckType->id }}" {{ (old('truck_type_id', $truck->truck_type_id) == $truckType->id) ? 'selected' : '' }}>{{ $truckType->name }} - {{ $truckType->generic_quantity }} cubic unit class</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if(!empty($errors->first('truck_type_id')))
                                                    <p style="color: red;" >{{$errors->first('truck_type_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 {{ !empty($errors->first('body_type')) ? 'has-error' : '' }}">
                                                <label for="body_type" class="control-label"><b style="color: red;">* </b> Truck Body Type : </label>
                                                <select class="form-control select2" name="body_type" id="body_type" tabindex="8" style="width: 100%;">
                                                    <option value="" {{ empty(old('body_type')) ? 'selected' : '' }}>Select body type</option>
                                                    @if(!empty($truckBodyTypes))
                                                        @foreach($truckBodyTypes as $key => $bodyType)
                                                            <option value="{{ $key }}" {{ (old('body_type', $truck->body_type) == $key) ? 'selected' : '' }}>
                                                                {{ $bodyType }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if(!empty($errors->first('body_type')))
                                                    <p style="color: red;" >{{$errors->first('body_type')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="volume" class="control-label"><b style="color: red;">* </b> Volume In Feet : </label>
                                                <input type="text" class="form-control number_only" name="volume" id="volume" placeholder="Volume in cubic feet" value="{{ old('volume', $truck->volume) }}" tabindex="7" maxlength="9">
                                                @if(!empty($errors->first('volume')))
                                                    <p style="color: red;" >{{$errors->first('volume')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                <label for="description" class="control-label">Description : </label>
                                                @if(!empty(old('description')))
                                                    <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="5">{{ $truck->description }}</textarea>
                                                @endif
                                                @if(!empty($errors->first('description')))
                                                    <p style="color: red;" >{{$errors->first('description')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6 {{ !empty($errors->first('insurance_upto')) ? 'has-error' : '' }}">
                                                <label for="insurance_upto" class="control-label"><b style="color: red;">* </b> Insurance Expires : </label>
                                                <input type="text" class="form-control decimal_number_only datepicker" name="insurance_upto" id="insurance_upto" placeholder="Insurance expires" value="{{ old('insurance_upto', $truck->insurance_upto->format('d-m-Y')) }}" tabindex="9">
                                                @if(!empty($errors->first('insurance_upto')))
                                                    <p style="color: red;" >{{$errors->first('insurance_upto')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 {{ !empty($errors->first('tax_upto')) ? 'has-error' : '' }}">
                                                <label for="tax_upto" class="control-label"><b style="color: red;">* </b> Road Tax Expires : </label>
                                                <input type="text" class="form-control decimal_number_only datepicker" name="tax_upto" id="tax_upto" placeholder="Road tax expires" value="{{ old('tax_upto', $truck->tax_upto->format('d-m-Y')) }}" tabindex="10">
                                                @if(!empty($errors->first('tax_upto')))
                                                    <p style="color: red;" >{{$errors->first('tax_upto')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6 {{ !empty($errors->first('fitness_upto')) ? 'has-error' : '' }}">
                                                <label for="fitness_upto" class="control-label"><b style="color: red;">* </b> Certificate of Fitness Expires : </label>
                                                <input type="text" class="form-control decimal_number_only datepicker" name="fitness_upto" id="fitness_upto" placeholder="Certificate of fitness expires" value="{{ old('fitness_upto', $truck->fitness_upto->format('d-m-Y')) }}" tabindex="11">
                                                @if(!empty($errors->first('fitness_upto')))
                                                    <p style="color: red;" >{{$errors->first('fitness_upto')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 {{ !empty($errors->first('permit_upto')) ? 'has-error' : '' }}">
                                                <label for="permit_upto" class="control-label"><b style="color: red;">* </b> Permit Expires : </label>
                                                <input type="text" class="form-control decimal_number_only datepicker" name="permit_upto" id="permit_upto" placeholder="Permit expires" value="{{ old('permit_upto', $truck->permit_upto->format('d-m-Y')) }}" tabindex="12">
                                                @if(!empty($errors->first('permit_upto')))
                                                    <p style="color: red;" >{{$errors->first('permit_upto')}}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6 {{ !empty($errors->first('pollution_upto')) ? 'has-error' : '' }}">
                                                <label for="pollution_upto" class="control-label"><b style="color: red;">* </b> Pollution Certificate Expires : </label>
                                                <input type="text" class="form-control decimal_number_only datepicker" name="pollution_upto" id="pollution_upto" placeholder="Poluution under control certificate expires" value="{{ old('pollution_upto', $truck->pollution_upto->format('d-m-Y')) }}">
                                                @if(!empty($errors->first('pollution_upto')))
                                                    <p style="color: red;" >{{$errors->first('pollution_upto')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div><br>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-3">
                                    <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="13">Clear</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary btn-block btn-flat update_button" tabindex="14">Submit</button>
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
    @else
        <h1 class="text-red">Selected Truck Record Not Found! <i class="fa fa-question text-blue" title="Selected truck record may be deleted or not available to you."></i></h1>
    @endif
</section>
<!-- /.content -->
@endsection
@section('scripts')
    <script src="/js/registrations/truckRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection