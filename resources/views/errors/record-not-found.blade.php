@extends('layouts.app')
@section('title', 'Record Not Found')
@section('content')
<section class="content-header">
    <h1>
        !
        <small>Record Not Found</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Record Not Found</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow"> !</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Record not found.</h3>

            <p>
                We could not find the <b>{{ $exception->getMessage() }}</b> record you were looking for.
                It might be deleted or you don't have the permission to access it.
                <br><br>If this happen again, please report to us.
                <br><b class="text-gray">Error Reference Code : EX/{{ $exception->getCode() }}</b>
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<!-- /.content -->
@endsection
