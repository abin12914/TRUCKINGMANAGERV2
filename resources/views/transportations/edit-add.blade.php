@extends('layouts.app')
@section('title', 'Transportation '. (empty($transportation) ? 'Add' : 'Edit'))
@section('content')
<section class="content-header">
    <h1>
        {{ empty($transportation) ? 'Add' : 'Edit' }}
        <small>Transportation</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('transportations.index') }}"> Transportation</a></li>
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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="float: left;">Transportation Details</h3>
                        <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="{{ empty($transportation) ? route('transportations.store') : route('transportations.update', $transportation->id) }}" method="post" id="transportation_registration_form" class="form-horizontal" autocomplete="off">
                    @if(!empty($transportation))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                {{-- including transportation form --}}
                                @include('components.forms.transportation')
                            </div>
                        </div>
                        <div class="clearfix"> </div><br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-2 ol-xs-2"></div>
                            <div class="col-lg-3 col-md-3 col-sm-4 ol-xs-4">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="14">Clear</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 ol-xs-4">
                                <button type="button" id="save_button" class="btn btn-{{ empty($transportation) ? 'primary ' : 'warning ' }} btn-block btn-flat" tabindex="13">
                                    {{ empty($transportation) ? 'Add' : 'Update' }}
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
@endsection
