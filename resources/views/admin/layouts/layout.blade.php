<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Vegetable-App</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for color variants for metronic and bootstrap custom elements and components" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
         <!-- BEGIN DATATABLE STYLES -->
        <link href="{{ asset('themes/admin/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <!-- END DATATABLE STYLES -->
        <link href="{{ asset('css/parsley.css?123') }}" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('themes/admin/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('themes/admin/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ asset('themes/admin/assets/layouts/layout3/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/admin/assets/layouts/layout3/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ asset('themes/admin/assets/layouts/layout3/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        @yield('styles')

    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid">
        <div class="page-wrapper">
            @include('admin.includes.Header')
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <!-- BEGIN CONTAINER -->
                    <div class="page-container">
                        <!-- BEGIN CONTENT -->
                        <div class="page-content-wrapper">
                            <!-- BEGIN CONTENT BODY -->
                            <!-- BEGIN PAGE CONTENT BODY -->
                            <div class="page-content">
                                @include('admin.includes.flashMsg')
                                @yield('content')
                            </div>
                            <!-- END PAGE CONTENT BODY -->
                            <!-- END CONTENT BODY -->
                        </div>
                        <!-- END CONTENT -->
                        <!-- BEGIN QUICK SIDEBAR -->
                        <a href="javascript:;" class="page-quick-sidebar-toggler">
                            <i class="icon-login"></i>
                        </a>
                      
                        <!-- END QUICK SIDEBAR -->
                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
            @include('admin.includes.footer')

        </div>
        
        <div class="quick-nav-overlay"></div>
        {!! Form::open(['method' => 'DELETE','id' => 'global_delete_form']) !!}
        {!! Form::hidden('id', 0,['id' => 'delete_id']) !!}
        {!! Form::close() !!}
        <script type="text/javascript">
                        var deleteConfirmMSG = "Are You sure Delete!";
                        var http_host_js = '{{ url("/") }}'; 
                        var http_toggleChange_js = '{{ route("change-toggle") }}';
                        var internalServerERR = 'Internal server error!';
        </script>
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('themes/admin/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
         <!-- END CORE PLUGINS -->
         <script src="{{ asset('themes/admin/assets/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript"></script> 
         <!-- BEGIN DATATABLE SCRIPTS -->
        <script src="{{ asset('themes/admin/assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
        <!-- END DATATABLE SCRIPTS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ asset('themes/admin/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{ asset('themes/admin/assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/layouts/layout3/scripts/demo.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('themes/admin/assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        <!-- BEGIN SELECT2 SCRIPTS -->
        <script src="{{ asset('themes/admin/assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
        <!-- END SELECT2 SCRIPTS -->

        <script src="{{ asset('js/jquery.bootstrap-growl.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/parsley.js?45') }}" type="text/javascript"></script>

        <script src="{{ asset('js/pages/admin/custom.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/pages/admin/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>

        <script type="text/javascript" src="{{ asset('js/formSubmitJs.js?498') }}"></script>


        @yield('scripts')

    </body>

</html>