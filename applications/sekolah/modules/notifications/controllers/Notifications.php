<?php

class Notifications extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_auth_check();
    }

    public function mark_as_read($notification_id) {
        $this->load->model('Notification_model');
        $user_id = $this->session->get('user_id');
        $this->Notification_model->mark_as_read($notification_id, $user_id);
        
        // Redirect back to the previous page
        $this->response->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
