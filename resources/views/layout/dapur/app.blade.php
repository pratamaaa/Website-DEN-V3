<!DOCTYPE html>
<html lang="en">

<head>
    <title>DEN</title>
    <!--[if lt IE 11]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="kres7" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- <link href="{{ asset('mainpro/images/favicon.png') }}" type="image/x-icon" rel="icon"> --}}
    <link rel="shortcut icon" href="{{ asset('theme/img/logo/den.png') }}" />
    <link href="{{ asset('mainpro/css/plugins/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mainpro/css/plugins/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('mainpro/select2/css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('mainpro/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('mainpro/simple-line-icons/css/simple-line-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <script src="{{ asset('mainpro/js/plugins/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('mainpro/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/pages/data-basic-custom.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/pages/data-select-custom.js') }}"></script>
    <script src="{{ asset('mainpro/js/popper.min.js') }}"></script>

    <script src="{{ asset('mainpro/js/plugins/sweetalert.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/pages/ac-alert.js') }}"></script>

    <script src="{{ asset('mainpro/js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/daterangepicker.js') }}"></script>
    <script src="{{ asset('mainpro/js/pages/ac-datepicker.js') }}"></script>
    <script src="{{ asset('mainpro/js/plugins/apexcharts.min.js') }}"></script>

    <!-- Form Validation -->
    <script src="{{ asset('mainpro/jqvalidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('mainpro/theglobals.js') }}"></script>

    {{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <style>
        /* reset default pcoded spacing */
        .pcoded-main-container {
            margin-left: 100px;
            padding: 15px;
            transition: all .3s;
        }

        /* sidebar width */
        .pcoded-navbar {
            width: 230px;
        }

        /* content wrapper */
        .pcoded-content {
            margin: 0;
            padding: 0;
        }

        /* inner wrapper */
        .pcoded-inner-content {
            padding: 0;
        }

        /* page wrapper */
        .page-wrapper {
            padding: 10px;
        }

        /* ketika sidebar collapse */
        .pcoded-navbar.navbar-collapsed {
            width: 80px;
        }

        .pcoded-navbar.navbar-collapsed~.pcoded-main-container {
            margin-left: 80px;
        }
    </style>
</head>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @include('layout.dapur.header')
    @include('layout.dapur.navigasi')
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">

                    <div class="main-body">
                        <div class="page-wrapper">

                            @yield('content')

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('mainpro/js/pcoded.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</body>

</html>
