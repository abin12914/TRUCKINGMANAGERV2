@extends('layouts.app')
@section('title', 'TM Error')
@section('content')
<section class="content-header">
    <h1>
        TM
        <small>Error</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">TM error</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow"> TM</h2>

        <div class="error-content">
            <h3> Oops! Houston, we have a problem.</h3>
            <p>
                <br>For data security last request is canceled. Try again please.
                <br><br>If happen again please report to us.
                <br><b class="text-gray" id="exception_code">Error Reference Code : {{ $exception->getCode() }}</b>
                <br><b class="text-gray" style="display: none;" id="exception_message">Error Message : {{ $exception->getMessage() }}</b>
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<!-- /.content -->
@endsection
