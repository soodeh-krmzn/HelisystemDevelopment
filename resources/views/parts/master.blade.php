<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-rtl.min.css') }}">
    <!-- Persian Data Picker -->
    <link rel="stylesheet" href="{{ asset('dist/css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">

    <!-- template rtl version -->
    <link rel="stylesheet" href="{{ asset('dist/css/custom-style.css') }}">

    @yield('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('sweetalert::alert')

        @include('parts.nav')

        @include('parts.aside')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('parts.breadcrumb')

            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-sm-none d-md-block">
                ساخته شده با
                <i class="fa fa-heart text-danger"></i>
                در
            </div>
            <!-- Default to the left -->
            <strong><a href="https://gratech.ir">گراتک</a></strong>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <!-- PAGE PLUGINS -->
    <!-- SparkLine -->
    <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- jVectorMap -->
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- Persian Datepicker -->
    <script src="{{ asset('plugins/persiandatepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('plugins/persiandatepicker/persian-datepicker.min.js') }}"></script>
    {{-- select2 --}}
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $("a[data-confirm-delete=true]").on('click', function(event) {
            event.preventDefault();
        });
        $(function() {
            $("#from, #to").persianDatepicker({
                initialValue: false,
                obsever: true,
                format: 'YYYY/MM/DD',
                autoClose: true
            });
            $(".select2").select2();
        });

    </script>
    @yield('scripts')

</body>

</html>
