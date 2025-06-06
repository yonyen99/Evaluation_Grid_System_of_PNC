<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Evaluation Grid System OF PNC
    </title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="{{ asset('dashboard/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('dashboard/css/dashboard.css?v=1.0.0') }}" rel="stylesheet" />
    <link href="{{ asset('dashboard/demo/demo.css') }}" rel="stylesheet" />
    @yield('stylesheet')
</head>

<body class="">
    <div class="wrapper">
        <!-- sidebar -->
        <div class="wrapper">
            @include('partials.sidebar')
            <div class="main-panel">
                <!-- Header -->
                @include('partials.navbar')

                <!--body dahboard-->
                <div class="content">
                    @yield('content')
                </div>

                <!-- footer -->
                @include('partials.footer')

                <!-- setting change color-->
                {{-- <div class="fixed-plugin">
                    <div class="dropdown show-dropdown">
                        <a href="#" data-toggle="dropdown">
                            <i class="fa fa-cog fa-2x"> </i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header-title"> Sidebar Background</li>
                            <li class="adjustments-line">
                                <a href="javascript:void(0)" class="switch-trigger background-color">
                                    <div class="badge-colors text-center">
                                        <span class="badge filter badge-info" data-color="blue"></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </a>
                            </li>
                            <li class="adjustments-line text-center color-change">
                                <span class="color-label">LIGHT MODE</span>
                                <span class="badge light-badge mr-2"></span>
                                <span class="badge dark-badge ml-2"></span>
                                <span class="color-label">DARK MODE</span>
                            </li>
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>


    <!--   Core JS Files   -->
    <script src=" {{ asset('dashboard/js/core/jquery.min.js') }}"></script>
    <script src=" {{ asset('dashboard/js/core/popper.min.js') }}"></script>
    <script src=" {{ asset('dashboard/js/core/bootstrap.min.js') }}"></script>
    <script src=" {{ asset('dashboard/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <!--  Google Maps Plugin    -->
    <!-- Place this tag in your head or just before your close body tag. -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chart JS -->
    <script src=" {{ asset('dashboard/js/plugins/chartjs.min.js') }}"></script>
    <!--  Notifications Plugin    -->
    <script src=" {{ asset('dashboard/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
    <script src=" {{ asset('dashboard/js/black-dashboard.min.js?v=1.0.0') }}"></script><!-- Black Dashboard DEMO methods, don't include it in your project! -->
    <script src=" {{ asset('dashboard/demo/demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $().ready(function() {
                $sidebar = $('.sidebar');
                $navbar = $('.navbar');
                $main_panel = $('.main-panel');

                $full_page = $('.full-page');

                $sidebar_responsive = $('body > .navbar-collapse');
                sidebar_mini_active = true;
                white_color = false;

                window_width = $(window).width();

                fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();



                $('.fixed-plugin a').click(function(event) {
                    if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (window.event) {
                            window.event.cancelBubble = true;
                        }
                    }
                });

                $('.fixed-plugin .background-color span').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('color');                    

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data', new_color);
                    }

                    if ($main_panel.length != 0) {
                        $main_panel.attr('data', new_color);
                    }

                    if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data', new_color);
                    }
                });

                $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
                    var $btn = $(this);

                    if (sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        sidebar_mini_active = false;
                        blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
                    } else {
                        $('body').addClass('sidebar-mini');
                        sidebar_mini_active = true;
                        blackDashboard.showSidebarMessage('Sidebar mini activated...');
                    }

                    // we simulate the window Resize so the charts will get updated in realtime.
                    var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 180);

                    // we stop the simulation of Window Resize after the animations are completed
                    setTimeout(function() {
                        clearInterval(simulateWindowResize);
                    }, 1000);
                });

                $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
                    var $btn = $(this);

                    if (white_color == true) {

                        $('body').addClass('change-background');
                        setTimeout(function() {
                            $('body').removeClass('change-background');
                            $('body').removeClass('white-content');
                        }, 900);
                        white_color = false;
                    } else {

                        $('body').addClass('change-background');
                        setTimeout(function() {
                            $('body').removeClass('change-background');
                            $('body').addClass('white-content');
                        }, 900);

                        white_color = true;
                    }
                });

                $('.light-badge').click(function() {
                    $('body').addClass('white-content');
                });

                $('.dark-badge').click(function() {
                    $('body').removeClass('white-content');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            demo.initDashboardPageCharts();
        });
    </script>
    <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
    <script>
        window.TrackJS &&
            TrackJS.install({
                token: "ee6fab19c5a04ac1a32a645abde4613a",
                application: "black-dashboard-free"
            });
    </script>
    @yield('script')
</body>

</html>
