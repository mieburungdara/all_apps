<?php
// Since the sidebar is a generic template, it needs to initialize the session
// on its own to get the user data, if it hasn't been started already.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_nama = $_SESSION['user_nama'] ?? 'Guest';
?>
      <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header (mini Sidebar mode) -->
        <div class="smini-visible-block">
          <div class="content-header bg-black-10">
            <a class="fw-semibold text-white tracking-wide" href="index.html">
              D<span class="opacity-75">x</span>
            </a>
          </div>
        </div>
        <!-- END Side Header (mini Sidebar mode) -->

        <!-- Side Header (normal Sidebar mode) -->
        <div class="smini-hidden">
          <div class="content-header justify-content-lg-center">
            <a class="fw-semibold text-white tracking-wide" href="/sekolah/dashboard">
              Dash<span class="opacity-75">mix</span>
              <span class="fw-normal">Sekolah</span>
            </a>
            <div class="d-lg-none">
              <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                <i class="fa fa-times-circle"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- END Side Header (normal Sidebar mode) -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Actions -->
          <div class="content-side content-side-full text-center bg-black-10">
            <div class="smini-hide">
              <img class="img-avatar" src="/assets/media/avatars/avatar15.jpg" alt="">
              <div class="mt-2 mb-1 fw-semibold"><?php echo htmlspecialchars($user_nama); ?></div>
              <a class="text-white-50 me-1" href="#">
                <i class="fa fa-fw fa-user-cog"></i>
              </a>
              <a class="text-white-50 me-1" href="#">
                <i class="fa fa-fw fa-cog"></i>
              </a>
              <a class="text-white-50" href="/sekolah/users/logout">
                <i class="fa fa-fw fa-sign-out-alt"></i>
              </a>
            </div>
          </div>
          <!-- END Side Actions -->

          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link active" href="/sekolah/dashboard">
                  <i class="nav-main-link-icon fa fa-hospital"></i>
                  <span class="nav-main-link-name">Overview</span>
                </a>
              </li>
              <li class="nav-main-heading">Manage</li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                  <i class="nav-main-link-icon fa fa-user-circle"></i>
                  <span class="nav-main-link-name">Siswa</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                  <i class="nav-main-link-icon fa fa-calendar"></i>
                  <span class="nav-main-link-name">Absensi</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                  <i class="nav-main-link-icon fa fa-money-bill-wave"></i>
                  <span class="nav-main-link-name">Keuangan</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
      </nav>