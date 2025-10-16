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

    public function users() {
        $this->load->model('Auth_model');
        // Check if the authenticated user is an admin
        if (!$this->Auth_model->user_has_role($this->api_user['id'], 'admin')) {
            $this->response->set_status_code(403)->json(['error' => 'Forbidden', 'message' => 'You do not have permission to access this resource.']);
            exit;
        }

        $this->load->model('Users_model');

        // Pagination parameters
        $page = (int)($this->input->get('page', 1));
        $limit = (int)($this->input->get('limit', 20));
        $offset = ($page - 1) * $limit;

        $users = $this->Users_model->get_all_users($limit, $offset);
        $total_records = $this->Users_model->get_total_users_count();
        $total_pages = ceil($total_records / $limit);

        // Sanitize data before sending
        $sanitized_users = array_map(function($user) {
            unset($user['password']);
            unset($user['api_key']);
            return $user;
        }, $users);

        $response = [
            'data' => $sanitized_users,
            'pagination' => [
                'total_records' => $total_records,
                'current_page' => $page,
                'total_pages' => $total_pages,
                'limit' => $limit
            ]
        ];

        $this->response->json($response);
    }

    public function user($id) {
        $this->load->model('Auth_model');
        
        $is_admin = $this->Auth_model->user_has_role($this->api_user['id'], 'admin');
        $is_self = ($this->api_user['id'] == $id);

        // Allow access if user is an admin or is requesting their own data
        if (!$is_admin && !$is_self) {
            $this->response->set_status_code(403)->json(['error' => 'Forbidden', 'message' => 'You do not have permission to access this resource.']);
            exit;
        }

        $this->load->model('Users_model');
        $user = $this->Users_model->get_user_by_id($id);

        if (!$user) {
            $this->response->set_status_code(404)->json(['error' => 'Not Found', 'message' => 'User not found.']);
            exit;
        }

        // Sanitize data
        unset($user['password']);
        
        // An admin should not see another user's API key.
        // A user should see their own API key.
        if ($is_admin && !$is_self) {
            unset($user['api_key']);
        }

        $this->response->json($user);
    }
}
