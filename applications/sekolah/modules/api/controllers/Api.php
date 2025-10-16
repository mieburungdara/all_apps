<?php

class Api extends Controller {

    protected $api_user = null;

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        // All methods in this controller require API authentication
        $this->_api_auth_check();
    }

    public function me() {
        // The _api_auth_check method has already populated $this->api_user
        // We can now return it as a JSON response.
        // It's good practice to remove sensitive data like the password hash.
        unset($this->api_user['password']);
        
        $this->response->json($this->api_user);
    }
}
