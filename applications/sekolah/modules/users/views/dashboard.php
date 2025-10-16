<div class="content">
  <div class="row">
    <div class="col-12">
      <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Welcome</h3>
        </div>
        <div class="block-content">
          <p>You have successfully logged in and this is your dashboard.</p>
          <?php if ($this->session->has_flash('success')): ?>
              <div class="alert alert-success"><?= $this->session->get_flash('success') ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
