<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-TileColor" content="#162946">
    <meta name="theme-color" content="#e67605">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="<?= theme('/assets/images/favicon.ico', CONF_VIEW_ADMIN); ?>" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= theme('/assets/images/favicon.ico', CONF_VIEW_ADMIN); ?>" />

    <?= $head; ?>

    <!-- Bootstrap Css -->
    <link href="<?= theme('/assets/plugins/bootstrap-4.3.1/css/bootstrap.min.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />

    <!-- Dashboard Css -->
    <link href="<?= theme('/assets/css/style.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />
    <link href="<?= theme('/assets/css/admin-custom.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />

    <!-- Sidemenu Css -->
    <link href="<?= theme('/assets/plugins/sidemenu/sidemenu.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />

    <!-- Custom scroll bar css-->
    <link href="<?= theme('/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />

    <!---Font icons-->
    <link href="<?= theme('/assets/css/icons.css', CONF_VIEW_ADMIN); ?>" rel="stylesheet" />

    <!-- Color-Skins -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= theme('/assets/color-skins/color13.css', CONF_VIEW_ADMIN); ?>" />

    <link rel="stylesheet" type="text/css" href="<?= url('/shared/styles/load.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= url('/shared/styles/boot.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= url('/shared/styles/styles.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= url('/shared/styles/message.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= theme('/assets/css/custom.css', CONF_VIEW_ADMIN); ?>" />

</head>

<body class="app sidebar-mini" data-url="<?= url() ?>">
    <?php
    $photo = user()->photo();
    $userPhoto = ($photo ? image($photo, 300, 300) : url("/shared/images/avatar.jpg"));
    ?>
    <div class="ajax_load" style="z-index: 1042;">
        <div class="ajax_load_box">
            <div class="ajax_preloader">
                <img src="<?= theme('assets/images/preloader.gif', CONF_VIEW_ADMIN); ?>">
            </div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>
    <div class="ajax_response" style="margin-top: 50px;"><?= flash(); ?></div>

    <!--Loader-->
    <div id="global-loader">
        <img src="<?= theme('/assets/images/loader.svg', CONF_VIEW_ADMIN); ?>" class="loader-img " alt="">
    </div>
    <!--/Loader-->

    <!--Page-->
    <div class="page">
        <div class="page-main">

            <!--Header-->
            <div class="app-header1 header py-1 d-flex">
                <div class="container-fluid">
                    <div class="d-flex">
                        <a class="header-brand d-flex justify-content-center align-items-center" href="<?= url('/admin/dash'); ?>">
                            <h2 class="mb-0"><?= CONF_SITE_NAME; ?></h2>
                        </a>
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a>
                        <div class="header-navicon">
                            <a href="#" data-toggle="search" class="nav-link d-lg-none navsearch-icon">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                        <div class="d-flex order-lg-2 ml-auto">
                            <div class="dropdown d-none d-md-flex">
                                <a class="nav-link icon full-screen-link">
                                    <i class="fe fe-maximize-2" id="fullscreen-button"></i>
                                </a>
                            </div>
                            <div class="dropdown ">
                                <a href="#" class="nav-link pr-0 leading-none user-img" data-toggle="dropdown">
                                    <img src="<?= $userPhoto ?>" alt="profile-img" class="avatar avatar-md brround">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow ">
                                    <a class="dropdown-item" href="<?= url('/admin/users/user/' . user()->id); ?>">
                                        <i class="dropdown-icon icon icon-user"></i> Perfil
                                    </a>
                                    <a class="dropdown-item" href="<?= url('/admin/logoff'); ?>">
                                        <i class="dropdown-icon icon icon-logoff"></i> Sair
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Header-->

            <!-- Sidebar menu-->
            <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
            <aside class="app-sidebar doc-sidebar">
                <div class="app-sidebar__user clearfix">
                    <div class="dropdown user-pro-body">
                        <div>
                            <img src="<?= $userPhoto; ?>" alt="user-img" class="avatar avatar-lg brround">
                            <a href="<?= url('/admin/users/user/' . user()->id); ?>" class="profile-img">
                                <span class="fa fa-pencil" aria-hidden="true"></span>
                            </a>
                        </div>
                        <div class="user-info">
                            <h2><?= user()->fullName(); ?></h2>
                        </div>
                    </div>
                </div>
                <ul class="side-menu">
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/dash/home'); ?>"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">Dashboard</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/clients/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Clientes</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/tickets/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Boletos</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/charges/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Cobranças</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/finisheds/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Pagos</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/agreements/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Acordos</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/agreeds/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Acordados</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/reports/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Relatórios</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item" href="<?= url('/admin/users/home'); ?>"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Usuários</span></a>
                    </li>
                </ul>
            </aside>
            <!--/Sidebar menu-->

            <?= $v->section("content"); ?>
        </div>

        <!--Footer-->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                        Copyright © 2021 <a href="#"><?= CONF_SITE_NAME; ?></a> All rights
                        reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!--Footer-->
    </div>
    <!--/Page-->

    <!-- Back to top -->
    <a href="#top" id="back-to-top"><i class="fa fa-rocket"></i></a>

    <!-- Axios -->
    <script src="<?= url('/shared/scripts/axios.js'); ?>"></script>

    <!-- JQuery js-->
    <script src="<?= theme('/assets/js/jquery-3.2.1.min.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme("/assets/js/apexcharts.min.js", CONF_VIEW_ADMIN); ?>"></script>

    <!-- Bootstrap js -->
    <script src="<?= theme('/assets/plugins/bootstrap-4.3.1/js/popper.min.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme('/assets/plugins/bootstrap-4.3.1/js/bootstrap.min.js', CONF_VIEW_ADMIN); ?>"></script>

    <!--JQueryVehiclerkline Js-->
    <script src="<?= theme('/assets/js/jquery.sparkline.min.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- Circle Progress Js-->
    <script src="<?= theme('/assets/js/circle-progress.min.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- Star Rating Js-->
    <script src="<?= theme('/assets/plugins/rating/jquery.rating-stars.js', CONF_VIEW_ADMIN); ?>"></script>

    <!--Counters -->
    <script src="<?= theme('/assets/plugins/counters/counterup.min.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme('/assets/plugins/counters/waypoints.min.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- Fullside-menu Js-->
    <script src="<?= theme('/assets/plugins/sidemenu/sidemenu.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- CHARTJS CHART -->
    <script src="<?= theme('/assets/plugins/chart/chart.bundle.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme('/assets/plugins/chart/utils.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- Custom scroll bar Js-->
    <script src="<?= theme('/assets/plugins/scroll-bar/jquery.mCustomScrollbar.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- ECharts Plugin -->
    <script src="<?= theme('/assets/plugins/echarts/echarts.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme('/assets/plugins/echarts/echarts.js', CONF_VIEW_ADMIN); ?>"></script>
    <script src="<?= theme('/assets/js/index1.js', CONF_VIEW_ADMIN); ?>"></script>

    <!-- Custom Js-->
    <script src="<?= theme('/assets/js/admin-custom.js', CONF_VIEW_ADMIN); ?>"></script>

    <script src="<?= url('/shared/scripts/jquery.form.js'); ?>"></script>
    <script src="<?= url('/shared/scripts/scripts.js'); ?>"></script>
    <script src="<?= url('/shared/scripts/jquery.mask.js'); ?>"></script>
    <script src="<?= url('/shared/scripts/mask.js'); ?>"></script>
    <script src="<?= url('/shared/scripts/Ajax.js'); ?>"></script>
    <?= $v->section("scripts"); ?>
</body>

</html>