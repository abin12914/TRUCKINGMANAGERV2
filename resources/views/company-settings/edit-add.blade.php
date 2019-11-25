@extends('layouts.app')
@section('title', 'Settings')
@section('content')
<section class="content-header">
    <h1>
        General
        <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Settings</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">General Settings</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ route('company.settings.update') }}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                    @method('PUT')
                    @csrf()
                    <div class="box-body no-print">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Settings</th>
                                            <th style="width: 60%;">Description</th>
                                            <th style="width: 15%;">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Default date</td>
                                            <td>Default date set will be auto populated in forms[If empty, current date will be used]</td>
                                            <td>
                                                <input type="text" class="form-control decimal_number_only datepicker_reg" name="default_date" id="default_date" placeholder="Default date" tabindex="1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Driver Auto Selection</td>
                                            <td>Auto select driver and wage in transportation form when vehicle is selected</td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="hidden" value="0" name="driver_auto_selection">
                                                <input type="checkbox" value="1" name="driver_auto_selection" {{ !empty($settings->driver_auto_selection) ? "checked" : "" }} tabindex="2">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Contractor Auto Selection</td>
                                            <td>Auto select contractor account in transportation form when sites are selected</td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="hidden" value="0" name="contractor_auto_selection">
                                                <input type="checkbox" value="1" name="contractor_auto_selection" {{ !empty($settings->contractor_auto_selection) ? "checked" : "" }} tabindex="3">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Measurement Auto Selection</td>
                                            <td>Auto select material and measurements in transportation form when contractor and other fields are selected</td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="hidden" value="0" name="measurements_auto_selection">
                                                <input type="checkbox" value="1" name="measurements_auto_selection" {{ !empty($settings->measurements_auto_selection) ? "checked" : "" }} tabindex="4">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5.</td>
                                            <td>Purchase Auto Selection</td>
                                            <td>Auto select purchase details in supply form when supplier is selected</td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="hidden" value="0" name="purchase_auto_selection">
                                                <input type="checkbox" value="1" name="purchase_auto_selection" {{ !empty($settings->purchase_auto_selection) ? "checked" : "" }} tabindex="5">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6.</td>
                                            <td>Sale Auto Selection</td>
                                            <td>Auto select sale details in supply form when customer is selected</td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="hidden" value="0" name="sale_auto_selection">
                                                <input type="checkbox" value="1" name="sale_auto_selection" {{ !empty($settings->sale_auto_selection) ? "checked" : "" }} tabindex="6">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"> </div><br>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-0"></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                            <button type="button" class="btn btn-warning update_button btn-block btn-flat" tabindex="7">
                                Update
                            </button>
                        </div>
                        <!-- /.col -->
                    </div><br>
                </form>
            </div>
            <!-- /.box primary -->
        </div>
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
