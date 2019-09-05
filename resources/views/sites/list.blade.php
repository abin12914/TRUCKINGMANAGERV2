@extends('layouts.app')
@section('title', 'Excavator List')
@section('content')
 <section class="content-header">
    <h1>
        Excavator
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Excavator List</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row  no-print">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-header">
                    <form action="{{ route('sites.index') }}" method="get" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="site_type" class="control-label">Site Type : </label>
                                        <select class="form-control select2" name="site_type" id="site_type" style="width: 100%" tabindex="1">
                                            <option value="">Select site type</option>
                                            @if(!empty($siteTypes) && (count($siteTypes) > 0))
                                                @foreach($siteTypes as $key => $siteType)
                                                    <option value="{{ $key }}" {{ (old('site_type') == $key || $params['site_type']['paramValue'] == $key) ? 'selected' : '' }}>{{ $siteType }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'site_type'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-4">
                                        <label for="site_id" class="control-label">Site : </label>
                                        {{-- adding site select component --}}
                                        @component('components.selects.sites', ['selectedSiteId' => $params['site_id']['paramValue'], 'selectName' => 'site_id', 'tabindex' => 2])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'site_id'])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-4">
                                        <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                        {{-- adding no of records text component --}}
                                        @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 3])
                                        @endcomponent
                                        {{-- adding error_message p tag component --}}
                                        @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="5">Clear</button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.form end -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {{-- page header for printers --}}
                @include('sections.print-head')
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-center">Excavators List</h6>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 20%;">Name</th>
                                        <th style="width: 20%;">Place</th>
                                        <th style="width: 30%;">Address</th>
                                        <th style="width: 20%;">Site Type</th>
                                        <th style="width: 5%;" class="no-print">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($sites))
                                        @foreach($sites as $index => $site)
                                            <tr>
                                                <td>{{ $index + $sites->firstItem() }}</td>
                                                <td>{{ $site->name }}</td>
                                                <td>{{ $site->place }}</td>
                                                <td>{{ $site->address }}</td>
                                                <td>{{ $siteTypes[$site->site_type] }}</td>
                                                <td class="no-print">
                                                    <a href="{{ route('sites.edit', ['id' => $site->id]) }}" style="float: left;">
                                                        <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
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
                            @if(!empty($sites))
                                <div>
                                    Showing {{ $sites->firstItem(). " - ". $sites->lastItem(). " of ". $sites->total() }}<br>
                                </div>
                                <div class=" no-print pull-right">
                                    {{ $sites->appends(Request::all())->links() }}
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