<?php
$session = new Session();
$flash_types = ['success', 'error', 'warning', 'info'];

foreach ($flash_types as $type) {
    if ($session->has_flash($type)) {
        $message = $session->get_flash($type);
        $icon = '';
        $alert_class = '';

        switch ($type) {
            case 'success':
                $icon = 'fa-check-circle';
                $alert_class = 'alert-success';
                break;
            case 'error':
                $icon = 'fa-times-circle';
                $alert_class = 'alert-danger';
                break;
            case 'warning':
                $icon = 'fa-exclamation-triangle';
                $alert_class = 'alert-warning';
                break;
            case 'info':
                $icon = 'fa-info-circle';
                $alert_class = 'alert-info';
                break;
        }

        echo "<div class='alert {$alert_class} d-flex align-items-center' role='alert'>";
        echo "    <div class='flex-shrink-0'>";
        echo "        <i class='fa {$icon}'></i>";
        echo "    </div>";
        echo "    <div class='flex-grow-1 ms-3'>";
        echo "        <p class='mb-0'>{$message}</p>";
        echo "    </div>";
        echo "</div>";
    }
}
?>
