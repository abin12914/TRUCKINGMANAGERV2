@extends('layouts.app')
@section('title', 'Site '. empty($site) ? 'Add' : 'Edit')
@section('content')
<section class="content-header">
    <h1>
        {{ empty($site) ? 'Add' : 'Edit' }}
        <small>Site</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('sites.index') }}"> Site</a></li>
        <li class="active"> {{ empty($site) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Site Details</h3>
                    <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div><br>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($site) ? route('sites.store') : route('sites.update', $site->id) }}" method="post" class="form-horizontal" autocomplete="off">
                    @if(!empty($site))
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
                                            <label for="name" class="control-label"><b style="color: red;">* </b> Name: </label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', !empty($site) ? $site->name : null) }}" maxlength="100" tabindex="1">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="place" class="control-label"><b style="color: red;">* </b> Place: </label>
                                            <input type="text" class="form-control" name="place" id="place" placeholder="Place" value="{{ old('place', !empty($site) ? $site->place : null) }}" maxlength="100" minlength="3" tabindex="2">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'place'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="address" class="control-label"><b style="color: red;">* </b> Address: </label>
                                            @if(!empty(old('address')))
                                                <textarea class="form-control" name="address" id="address" rows="1" placeholder="Address" style="resize: none;" tabindex="3">{{ old('address') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="address" id="address" rows="1" placeholder="Address" style="resize: none;" tabindex="3">{{ !empty($site) ? $site->address : null }}</textarea>
                                            @endif
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="capacity" class="control-label"><b style="color: red;">* </b> Site Type: </label>
                                            <select class="form-control select2" name="site_type" id="site_type" tabindex="4">
                                                <option value="" {{ empty(old('site_type')) ? 'selected' : '' }}>Select site type</option>
                                                @if(!empty($siteTypes))
                                                    @foreach($siteTypes as $key => $siteType)
                                                        <option value="{{ $key }}" {{ old('site_type', !empty($site) ? $site->site_type : null) == $key ? 'selected' : '' }}>{{ $siteType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'site_type'])
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
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="6">Clear</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <button type="button" class="btn btn-{{ empty($site) ? 'primary submit-button ' : 'warning update_button ' }} btn-block btn-flat" tabindex="5">
                                    {{ empty($site) ? 'Add' : 'Update' }}
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
