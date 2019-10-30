@extends('layouts.app')
@section('title', '404-Page Not Found')
@section('content')
<section class="content-header">
    <h1>
        404
        <small>Page Not Found</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">404-Page Not Found</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

            <p>
                {{-- "You don't have the power to upset me. Stay calm... We got it covered." --}}
                We could not find the page you were looking for.<br />
                <a href="{{ route('dashboard') }}">return to dashboard</a>
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<!-- /.content -->
@endsection
