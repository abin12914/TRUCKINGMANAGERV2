@extends('layouts.app')
@section('title', 'Transportation Registration')
@section('content')
<section class="content-header">
    <h1>
        Transportation
        <small>Registartion</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('transportations.index') }}"> Transportation</a></li>
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
                <form action="{{route('transportations.update', $transportation->id) }}" method="post" id="transportation_registration_form" class="form-horizontal" autocomplete="off">
                    @csrf
                    @method('PUT')
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
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="13">Clear</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="save_button" class="btn btn-primary btn-block btn-flat" tabindex="14">Submit</button>
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

    </script>

    <script src="/js/registrations/transportationRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection
