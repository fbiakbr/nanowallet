<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>" />
    <!-- Sweetalert -->
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/sweetalert2.min.css') ?>">
    <!-- Datatables -->
    <link rel="stylesheet" href="<?= base_url('assets/datatables/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/datatables/table-datatable.css') ?>">
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="<?= base_url('admin/index') ?>">
                    <h4 class="font-weight-bold">SMART WALLET</h4>
                </a>
                <a class="navbar-brand brand-logo-mini" href="<?= base_url('admin/index') ?>"><img src="<?= base_url('assets/images/logo-mini.svg') ?>" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>
                    <li class="nav-item nav-logout d-none d-lg-block">
                        <a id="logout" class="nav-link" href="<?= base_url('admin/logout') ?>">
                            <i class="mdi mdi-power"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div>
                                <img src="<?= base_url('assets/images/smk.png') ?>" alt="profile" width="50">
                                <span class="login-status online"></span>
                                <!--change to offline or busy as needed-->
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">SMART</span>
                                <span class="text-secondary text-small">ADMIN</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/index') ?>">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <span class="menu-title">Data Master</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/data_saldo') ?>">Data Saldo</a></li>
                                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/data_pemasukan') ?>">Data Pemasukan</a></li>
                                <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/data_pengeluaran') ?>">Data Pengeluaran</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/input_pemasukan') ?>">
                            <span class="menu-title">Tambah Saldo</span>
                            <i class="mdi mdi-tooltip-plus menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/tarik_saldo') ?>">
                            <span class="menu-title">Tarik Saldo</span>
                            <i class="mdi mdi-coin menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/kehilangan_kartu') ?>">
                            <span class="menu-title">Kehilangan Kartu</span>
                            <i class="mdi mdi-credit-card menu-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <?= $this->renderSection('content') ?>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © <a href="http://smknutirto.sch.id/" target="_blank">SMK MA'ARIF NU TIRTO</a> <?= date('Y') ?></span>
                        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Design & Develop by <a href="https://github.com/fbiakbr" target="_blank">Febiadi Wisnu Akbar</a></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="<?= base_url('assets/vendors/chart.js/Chart.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/chart.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.cookie.js') ?>" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('assets/js/misc.js') ?>"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script src="<?= base_url('assets/js/todolist.js') ?>"></script>
    <!-- End custom js for this page -->
    <!-- Sweetalert -->
    <script src="<?= base_url('assets/sweetalert/sweetalert2.all.min.js') ?>"></script>
    <!-- Datatables -->
    <script src="<?= base_url('assets/datatables/dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/datatables/dataTables.js') ?>"></script>
    <script src="<?= base_url('assets/datatables/jquery.min.js') ?>"></script>
    <script>
        let logout = document.getElementById('logout');

        logout.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan keluar dari aplikasi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8142ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('admin/logout') ?>";
                }
            })
        })
    </script>
</body>

</html>