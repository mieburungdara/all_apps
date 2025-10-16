<?php
$page_title = $page_title ?? 'Authentication';
?>
<!doctype html>
<html lang="en" class="remember-theme">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="shortcut icon" href="<?php echo asset_url('media/favicons/favicon.png'); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo asset_url('media/favicons/favicon-192x192.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset_url('media/favicons/apple-touch-icon-180x180.png'); ?>">
    <link rel="stylesheet" id="css-main" href="<?php echo asset_url('css/dashmix.min.css'); ?>">
    <link rel="stylesheet" id="css-theme" href="<?php echo asset_url('css/themes/xdream.min.css'); ?>">
    <script src="<?php echo asset_url('js/setTheme.js'); ?>"></script>
  </head>
  <body>
    <div id="page-container">
      <main id="main-container">
        <div class="bg-image" style="background-image: url('<?php echo asset_url("media/photos/photo22@2x.jpg"); ?>');">
          <div class="row g-0 bg-primary-op">
            <div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
              <div class="p-3 w-100">

                <?php 
                $session = Session::getInstance();
                if ($session->has_flash('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $session->get_flash('success'); ?>
                    </div>
                <?php elseif ($session->has_flash('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $session->get_flash('error'); ?>
                    </div>
                <?php elseif ($session->has_flash('info')): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $session->get_flash('info'); ?>
                    </div>
                <?php endif; ?>