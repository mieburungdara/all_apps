<?php

use League\OAuth2\Client\Provider\Google;

class Auth extends Controller {

    private $provider;

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load->model('Users_model');

        $config = $this->config->load('google_auth');

        $this->provider = new Google([
            'clientId'     => $config['clientId'],
            'clientSecret' => $config['clientSecret'],
            'redirectUri'  => $config['redirectUri'],
        ]);
    }

    public function login_google() {
        $authUrl = $this->provider->getAuthorizationUrl();
        $this->session->set('oauth2state', $this->provider->getState());
        $this->response->redirect($authUrl);
    }

    public function callback_google() {
        if (empty($this->input->get('state')) || ($this->input->get('state') !== $this->session->get('oauth2state'))) {
            $this->session->set('oauth2state', null);
            $this->session->set_flash('error', 'Invalid state. Please try again.');
            $this->response->redirect('/sekolah/users/login');
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $this->input->get('code')
            ]);

            $ownerDetails = $this->provider->getResourceOwner($token);

            $email = $ownerDetails->getEmail();
            $nama = $ownerDetails->getName();

            $user = $this->Users_model->get_user_by_email($email);

            if (!$user) {
                // Auto-register user
                $post_data = [
                    'nama' => $nama,
                    'email' => $email,
                    // Generate a random password as it's required, but won't be used for login
                    'password' => bin2hex(random_bytes(16))
                ];
                $user_id = $this->Users_model->register_user($post_data);
                $user = ['id' => $user_id, 'nama' => $nama]; // Prepare user data for session
            }

            // Log the user in
            $this->session->regenerate_id();
            $this->session->set('user_id', $user['id']);
            $this->session->set('user_nama', $user['nama']);
            $this->session->set_flash('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
            $this->response->redirect('/sekolah/dashboard');

        } catch (Exception $e) {
            $this->session->set_flash('error', 'Something went wrong: ' . $e->getMessage());
            $this->response->redirect('/sekolah/users/login');
        }
    }
}
