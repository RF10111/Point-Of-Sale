<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/admin/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>My Star</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../img/mercy.png" />

   <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../../assets/admin/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/admin/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/admin/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/admin/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../../assets/admin/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/admin/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/admin/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="../../img/mercy.png" alt="mercy" width="35px">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">my star</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">data master</span>
        </li>
        <li class="menu-item <?php echo ($current_page == '../index.php') ? 'active' : ''; ?>">
            <a href="../index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Data Customer</div>
            </a>
        </li>
        <li class="menu-item <?php echo ($current_page == '../mekanik.php') ? 'active' : ''; ?>">
            <a href="../mekanik.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div data-i18n="Analytics">Data Mekanik</div>
            </a>
        </li>
        <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Layouts">Data Supplier</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item <?php echo ($current_page == '../supplier.php') ? 'active' : ''; ?>">
                  <a href="../supplier.php" class="menu-link">
                    <div data-i18n="Analytics">Supplier</div>
                  </a>
                </li>
                <li class="menu-item <?php echo ($current_page == '../order.php') ? 'active' : ''; ?>">
                  <a href="../order.php" class="menu-link">
                    <div data-i18n="Analytics">Order Spare Parts</div>
                  </a>
                </li>
              </ul>
              <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wrench"></i>
                <div data-i18n="Layouts">Data Spareparts</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item <?php echo ($current_page == '../sp_new.php') ? 'active' : ''; ?>">
                  <a href="../sp_new.php" class="menu-link">
                    <div data-i18n="Analytics">Spare Part Baru</div>
                  </a>
                </li>
                <li class="menu-item <?php echo ($current_page == '../sp_used.php') ? 'active' : ''; ?>">
                  <a href="../sp_used.php" class="menu-link">
                    <div data-i18n="Analytics">Spare Part Used</div>
                  </a>
                </li>
              </ul>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Penjualan</span>
        </li>
        <li class="menu-item <?php echo ($current_page == '../jual.php') ? 'active' : ''; ?>">
          <a href="../jual.php" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cart"></i>
            <div data-i18n="Analytics">Penjualan Spare Parts</div>
          </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Administrasi</span>
        </li>
        <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-extension"></i>
                <div data-i18n="Layouts">Data Administrasi</div>
              </a>
            <ul class="menu-sub">
              <li class="menu-item <?php echo ($current_page == '../wo.php') ? 'active' : ''; ?>">
                <a href="../wo.php" class="menu-link">
                    <div data-i18n="Analytics">Work Order</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../reservasi.php') ? 'active' : ''; ?>">
                <a href="../reservasi.php" class="menu-link">
                    <div data-i18n="Analytics">Reservasi</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../estimasi.php') ? 'active' : ''; ?>">
                <a href="../estimasi.php" class="menu-link">
                    <div data-i18n="Analytics">Estimasi</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../invoice.php') ? 'active' : ''; ?>">
                <a href="../invoice.php" class="menu-link">
                    <div data-i18n="Analytics">Invoice</div>
                </a>
              </li>
              </li>
            </ul>
            <li class="menu-header small text-uppercase">
            <span class="menu-header-text">History</span>
        </li>
        <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Layouts">Data History Pelanggan</div>
              </a>
            <ul class="menu-sub">
              <li class="menu-item <?php echo ($current_page == '../history_workorder.php') ? 'active' : ''; ?>">
                <a href="../history_workorder.php" class="menu-link">
                    <div data-i18n="Analytics">History Work Order</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../history_estimasi.php') ? 'active' : ''; ?>">
                <a href="../history_estimasi.php" class="menu-link">
                  <div data-i18n="Analytics">History Estimasi</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../history_invoice.php') ? 'active' : ''; ?>">
                <a href="../history_invoice.php" class="menu-link">
                    <div data-i18n="Analytics">History Invoice</div>
                </a>
              </li>
            </ul>
            <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan Bulanan</span>
        </li>
        <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Layouts">Penghasilan & Pengeluaran</div>
              </a>
            <ul class="menu-sub">
              <li class="menu-item <?php echo ($current_page == '../penghasilan_invoice.php') ? 'active' : ''; ?>">
                <a href="../penghasilan_invoice.php" class="menu-link">
                    <div data-i18n="Analytics">Penghasilan Invoice</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../penghasilan_sparepart.php') ? 'active' : ''; ?>">
                <a href="../penghasilan_sparepart.php" class="menu-link">
                    <div data-i18n="Analytics">Penghasilan Penjualan Sparepart</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../pengeluaran_sparepart.php') ? 'active' : ''; ?>">
                <a href="../pengeluaran_sparepart.php" class="menu-link">
                    <div data-i18n="Analytics">Pembelian Spare Part</div>
                </a>
              </li>
              <li class="menu-item <?php echo ($current_page == '../cashflow.php') ? 'active' : ''; ?>">
                <a href="../cashflow.php" class="menu-link">
                    <div data-i18n="Analytics">Pengeluaran Cashflow</div>
                </a>
              </li>
              </li>
            </ul>
        </ul>
      </ul>
  </aside>
        <!-- / Menu -->
         <div class="menu-inner-shadow"></div>

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  Halaman Admin Bengkel
                </div>
              </div>
              <!-- /Search -->
               <ul class="navbar-nav flex-row align-items-center ms-auto">
           <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../../assets/admin/assets/img/avatars/6.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->
            <!-- Content wrapper -->
          <div class="content-wrapper">