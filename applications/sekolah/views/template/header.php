<?php
$session = new Session();
$user = (new Users_model())->get_user_by_id($session->get('user_id'));
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - App</title>
    <link rel="stylesheet" id="css-main" href="/dashmix/assets/css/dashmix.min.css">
  </head>
  <body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
