<?php
// You can pass variables to the header, for example, the page title
$page_title = $page_title ?? 'Dashboard';
?>
<!doctype html>
<html lang="en" class="remember-theme">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title><?php echo htmlspecialchars($page_title); ?></title>

    <meta name="description" content="Your Application Description">
    <meta name="author" content="Your Name">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="/assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <link rel="stylesheet" id="css-main" href="/assets/css/dashmix.min.css">
    <link rel="stylesheet" id="css-theme" href="/assets/css/themes/xdream.min.css">
    <!-- END Stylesheets -->

    <script src="/assets/js/setTheme.js"></script>
  </head>

  <body>
    <div id="page-container" class="sidebar-o sidebar-dark side-scroll page-header-fixed main-content-boxed">
