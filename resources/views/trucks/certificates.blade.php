@extends('layouts.app')
@section('title', 'Truck List')
@section('content')
<section class="content-header">
    <h1>
        Truck
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a> Truck</a></li>
        <li class="active"> List</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {{-- page header for printers --}}
                @include('sections.print-head')
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-center">Trucks List</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 10%;">Register Number</th>
                                        <th style="width: 14%;">Insurance upto</th>
                                        <th style="width: 14%;">Road tax upto</th>
                                        <th style="width: 14%;">Fitness upto</th>
                                        <th style="width: 14%;">Permit upto</th>
                                        <th style="width: 14%;">Pollution upto</th>
                                        <th style="width: 15%;" class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($trucks))
                                        @foreach($trucks as $index => $truck)
                                            <tr>
                                                <td>{{ $index + $trucks->firstItem() }}</td>
                                                <td>{{ $truck->reg_number }}</td>
                                                @foreach ($certificateTypes as $key => $certificate)
                                                    @if($truck->isCertExpired($certificate))
                                                        <td class="text-red">
                                                            {{ $truck->$certificate->format('d-m-Y') }}&nbsp;
                                                            <i class="fa fa-times" title="Expired.."></i>
                                                        </td>
                                                    @elseif($truck->isCertCritical($certificate))
                                                        <td class="text-orange">
                                                            {{ $truck->$certificate->format('d-m-Y') }}&nbsp;
                                                            <i class="fa fa-clock-o" title="Expiring soon.."></i>
                                                        </td>
                                                    @else
                                                        <td class="text-green">
                                                            {{ $truck->$certificate->format('d-m-Y') }}&nbsp;
                                                            <i class="fa fa-check" title="Valid.."></i>
                                                        </td>
                                                    @endif
                                                @endforeach
                                                <td class="no-print">
                                                    <a href="{{ route('expense.certificate.renew', ['truckId' => $truck->id]) }}">
                                                        <button type="button" class="btn btn-info"> Renew</button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(!empty($trucks))
                                <div>
                                    Showing {{ $trucks->firstItem(). " - ". $trucks->lastItem(). " of ". $trucks->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $trucks->appends(Request::all())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.boxy -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
