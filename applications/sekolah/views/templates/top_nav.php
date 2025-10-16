      <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
          <!-- Left Section -->
          <div>
            <button type="button" class="btn btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
            <form class="d-none d-lg-inline-block" action="#" method="POST">
              <input type="text" class="form-control form-control-alt rounded-pill px-4" placeholder="Search.." id="page-header-search-input-full" name="page-header-search-input-full">
            </form>
          </div>
          <!-- END Left Section -->

          <!-- Right Section -->
          <div>
            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-flag"></i>
                <span class="badge rounded-pill bg-success">3</span>
              </button>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg rounded-0 p-0" aria-labelledby="page-header-notifications-dropdown">
                <div class="bg-image" style="background-image: url('/assets/media/photos/photo25.jpg');">
                  <div class="bg-primary-op fw-semibold text-white text-center px-3 py-4">
                    <div class="fs-4">Notifications</div>
                  </div>
                </div>
                <ul class="nav-items my-2">
                  <li>
                    <a class="d-flex text-dark py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 mx-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                      </div>
                      <div class="flex-grow-1 fs-sm pe-2">
                        <div class="fw-semibold">New student was added!</div>
                        <div class="text-muted">15 min ago</div>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- END Notifications Dropdown -->
          </div>
          <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Loader -->
        <div id="page-header-loader" class="overlay-header bg-primary-darker">
          <div class="content-header">
            <div class="w-100 text-center">
              <i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
            </div>
          </div>
        </div>
        <!-- END Header Loader -->
      </header>