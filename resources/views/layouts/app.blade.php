<!DOCTYPE html>
<html>
    <head>
        <!-- sections/head.main.blade -->
        @include('sections.head')

        {{-- additional stylesheet includes --}}
        @section('stylesheets')
        @show
    </head>
    <body class="hold-transition skin-green fixed sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <header class="main-header">
                <!-- sections/header.main.blade -->
                @include('sections.header')
            </header>

            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sections/leftsidebar.main.blade -->
                @include('sections.leftsidebar')
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Wrapper. Contains page content -->
                @section('content')
                @show
            </div>
            <!-- /.content-wrapper -->

            <!-- sections/footer.main.blade -->
            @include('sections.footer')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                @include('sections.rightsidebar')
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->
        @include('sections.scripts')

        {{-- additional js scripts includes --}}
        @section('scripts')
        @show

        {{-- message type and message for sweet alert --}}
        <script type="text/javascript">
            alertType    = "{{ Session::get('alert-class') }}";
            alertMessage = "{{ Session::get('message') }}";
            defaultDate  = "{{ !empty($settings) && !empty($settings->default_date) ? $settings->default_date->format('d-m-Y') : (\Carbon\Carbon::now()->format('d-m-Y')) }}";
        </script>

    </body>
</html>
