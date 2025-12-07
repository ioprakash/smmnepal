<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        Session::init();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = md5($_POST['password']); // Legacy MD5 hash

            $stmt = $this->db->prepare("SELECT * FROM clients WHERE (username = :username OR email = :email) AND password = :password");
            $stmt->execute(['username' => $username, 'email' => $username, 'password' => $password]);
            $user = $stmt->fetch();

            if ($user) {
                Session::set('user_id', $user['client_id']);
                Session::set('username', $user['username']);
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            } else {
                $error = "Invalid username or password";
            }
        }
        require_once '../views/login.php';
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $error = "Passwords do not match";
            } else {
                // Check if username or email exists
                $stmt = $this->db->prepare("SELECT * FROM clients WHERE username = :username OR email = :email");
                $stmt->execute(['username' => $username, 'email' => $email]);
                if ($stmt->rowCount() > 0) {
                    $error = "Username or Email already exists";
                } else {
                    $hashed_password = md5($password); // Legacy MD5
                    $apikey = md5(uniqid(rand(), true));
                    $ref_code = substr(bin2hex(random_bytes(18)), 5, 6);

                    $stmt = $this->db->prepare("INSERT INTO clients (name, username, email, password, apikey, ref_code, register_date) VALUES (:name, :username, :email, :password, :apikey, :ref_code, NOW())");
                    if ($stmt->execute([
                        'name' => $name,
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashed_password,
                        'apikey' => $apikey,
                        'ref_code' => $ref_code
                    ])) {
                        header('Location: ' . BASE_URL . '/login');
                        exit;
                    } else {
                        $error = "Registration failed";
                    }
                }
            }
        }
        require_once '../views/signup.php';
    }

    public function logout() {
        Session::destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
