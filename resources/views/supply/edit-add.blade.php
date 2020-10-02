@extends('layouts.app')
@section('title', 'Supply '. (empty($transportation) ? 'Add' : 'Edit'))
@section('content')
<section class="content-header">
    <h1>
        {{ empty($transportation) ? 'Add' : 'Edit' }}
        <small>Supply</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('transportations.index') }}"> Supply</a></li>
        <li class="active">{{ empty($transportation) ? 'Add' : 'Edit' }}</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    @if (!empty($errors->first('calculations')))
        <div class="alert alert-danger" id="calculation-error-message">
            <h4>
                {{ $errors->first('calculations') }}
            </h4>
        </div>
    @endif
    <!-- Main row -->
    <div class="row no-print">
        <div class="col-md-12">
            <!-- form start -->
            <form action="{{ empty($transportation) ? route('supply.store') : route('supply.update', $transportation->id) }}" method="post" id="supply_registration_form" class="form-horizontal" autocomplete="off">
                @if(!empty($transportation))
                    @method('PUT')
                @endif
                @csrf
                <!-- nav-tabs-custom -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#transportation_tab" data-toggle="tab">Transportation Details</a></li>
                        <li class=""><a href="#purchase_tab" data-toggle="tab">Purchase Details</a></li>
                        <li class=""><a href="#sale_tab" data-toggle="tab">Sale Details</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="transportation_tab">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">

                                        {{-- transportation form added --}}
                                        @include('components.forms.transportation')

                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="box-footer">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <button type="button" class="btn btn-default" disabled tabindex="-1">Prev</button>
                                            <a href="#purchase_tab" data-toggle="tab" class="arrows">
                                                <button type="button" class="btn btn-info pull-right" tabindex="13">Next</button>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.box-footer -->
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="purchase_tab">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">

                                        {{-- purchase form added --}}
                                        @include('components.forms.purchase')

                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="box-footer">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <a href="#transportation_tab" data-toggle="tab" class="arrows">
                                                <button type="button" class="btn btn-default" tabindex="-1">Prev</button>
                                            </a>
                                            <a href="#sale_tab" data-toggle="tab" class="arrows">
                                                <button type="button" class="btn btn-info pull-right" tabindex="7">Next</button>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.box-footer -->
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="sale_tab">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">

                                        {{-- purchase form added --}}
                                        @include('components.forms.sale')

                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="box-footer">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <a href="#purchase_tab" data-toggle="tab" class="arrows">
                                                <button type="button" class="btn btn-default" tabindex="-1">Prev</button>
                                            </a>
                                            <button type="button" id="save_button" class="btn btn-{{ empty($transportation) ? 'primary ' : 'warning ' }} pull-right" tabindex="7">
                                                {{ empty($transportation) ? 'Add' : 'Update' }}
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.box-footer -->
                                    <!-- /.col -->
                                </div>
                                    <!-- /.col -->
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /. nav-tabs-custom -->
            </form>
            <!-- /. form -->
        </div>
        <!-- /.col-md-12 -->
    </div>

    {{-- including processing modal --}}
    @include('components.modals.processing')

</section>
<!-- /.content -->
@endsection
@section('scripts')
    <script type="text/javascript">
        var secondDriverWageRatioGlobal = "{{ !empty($settings) ? ($settings->second_driver_wage_ratio ?? 0) : 0 }}";
    </script>
    <script src="/js/registrations/transportationRegistration.js"></script>
    <script src="/js/registrations/supplyRegistration.min.js"></script>
@endsection
