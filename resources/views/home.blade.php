@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12 connectedSortable">
            <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Expired Certificates</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="todo-list">
                        @forelse ($expiredCertTrucks as $key => $truck)
                            <li>
                                {{$loop->iteration}}.
                                <span class="text">{{ $truck->reg_number }}</span>

                                @foreach ($certificateTypes as $key => $certificate)
                                    @if($truck->isCertExpired($certificate))
                                        <small class="label label-danger" title="Expired">
                                            <i class="fa fa-clock-o"></i> {{ $key }}
                                        </small>
                                    @endif
                                @endforeach
                                <div class="tools">
                                    <a href="{{ route('expense.certificate.renew', $truck->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li>
                                <span class="text">No expired certificates</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                    <a href="{{ route('trucks.certificates') }}">
                        <button type="button" class="btn btn-default pull-right">
                            See list
                        </button>
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.Left col -->
        <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12 connectedSortable">
            <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Certificates Expiring Soon</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="todo-list">
                        @forelse ($criticalCertTrucks as $key => $truck)
                            <li>
                                {{$loop->iteration}}.
                                <span class="text">{{ $truck->reg_number }}</span>

                                @foreach ($certificateTypes as $key => $certificate)
                                    @if($truck->isCertCritical($certificate))
                                        <small class="label label-warning" title="Expired">
                                            <i class="fa fa-clock-o"></i> {{ $key }}
                                        </small>
                                    @endif
                                @endforeach
                                <div class="tools">
                                    <a href="{{ route('expense.certificate.renew', $truck->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li>
                                <span class="text">Nothing to show</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                    <a href="{{ route('trucks.certificates') }}">
                        <button type="button" class="btn btn-default pull-right">
                            See list
                        </button>
                    </a>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.Left col -->
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
