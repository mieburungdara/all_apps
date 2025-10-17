      <header id="page-header">
        <div class="content-header">
          <div class="space-x-1">
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
          </div>
          <div class="space-x-1">
            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-bell"></i>
                <?php
                // Fetch notifications directly in the view
                $notification_model = new Notification_model();
                $user_id = $session->get('user_id');
                $unread_notifications = $notification_model->get_unread_notifications($user_id);
                $unread_count = count($unread_notifications);
                if ($unread_count > 0) {
                  echo "<span class='badge bg-danger'>$unread_count</span>";
                }
                ?>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                  Notifications
                </div>
                <ul class="nav-items my-2">
                  <?php if ($unread_count > 0): ?>
                    <?php foreach ($unread_notifications as $notif): ?>
                      <li>
                        <a class="d-flex text-dark py-2" href="/sekolah/notifications/mark_as_read/<?= $notif['id'] ?>">
                          <div class="flex-shrink-0 mx-3">
                            <i class="fa fa-fw fa-info-circle text-info"></i>
                          </div>
                          <div class="flex-grow-1 fs-sm pe-2">
                            <div class="fw-semibold"><?= $notif['message'] ?></div>
                            <div class="text-muted"><?= date('d M Y, H:i', strtotime($notif['created_at'])) ?></div>
                          </div>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li class="p-2">
                      <div class="text-center fs-sm text-muted">No new notifications</div>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>

            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-user d-sm-none"></i>
                <span class="d-none d-sm-inline-block">Hi, <?= $user['nama'] ?? 'User' ?></span>
                <i class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-2">
                  <a class="dropdown-item" href="/sekolah/users/profile">
                    <i class="far fa-fw fa-user-circle me-1"></i> My Profile
                  </a>
                  <div role="separator" class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/sekolah/users/logout">
                    <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Sign Out
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <main id="main-container">
        <div class="content">
            <?php require_once APPPATH . 'views/template/partials/flash_messages.php'; ?>
        </div>
