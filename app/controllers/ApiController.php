<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->library('api');
    }

    public function login()
    {
        $this->api->require_method('POST');

        $input = $this->api->body();

        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        $stmt = $this->db->raw(
            'SELECT * FROM users WHERE username = ?',
            [$username]
        );

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $tokens = $this->api->issue_tokens([
            'id' => $user['id'],
             ]);

            $this->api->respond($tokens);

        } else {

            $this->api->respond_error('Invalid credentials', 401);

        }
    }
}