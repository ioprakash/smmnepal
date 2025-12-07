<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class Account {
    private $db;
    private $user;

    public function __construct() {
        Session::init();
        if (!Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->db = Database::getInstance()->getConnection();
        
        // Get current user data
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE client_id = :id");
        $stmt->execute(['id' => Session::get('user_id')]);
        $this->user = $stmt->fetch();
    }

    public function index() {
        $error = '';
        $success = '';

        // Handle password change
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            if (md5($currentPass) !== $this->user['password']) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPass) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'Passwords do not match.';
            } else {
                $stmt = $this->db->prepare("UPDATE clients SET password = :pass WHERE client_id = :id");
                if ($stmt->execute(['pass' => md5($newPass), 'id' => $this->user['client_id']])) {
                    // Log activity
                    $logStmt = $this->db->prepare("INSERT INTO client_report (client_id, action, report_ip, report_date) VALUES (:id, :action, :ip, NOW())");
                    $logStmt->execute([
                        'id' => $this->user['client_id'],
                        'action' => 'User password has been changed',
                        'ip' => $_SERVER['REMOTE_ADDR']
                    ]);
                    $success = 'Password changed successfully!';
                } else {
                    $error = 'Failed to change password.';
                }
            }
        }

        // Handle API key regeneration
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regenerate_api'])) {
            $newApiKey = bin2hex(random_bytes(16));
            $stmt = $this->db->prepare("UPDATE clients SET apikey = :key WHERE client_id = :id");
            if ($stmt->execute(['key' => $newApiKey, 'id' => $this->user['client_id']])) {
                // Log activity
                $logStmt = $this->db->prepare("INSERT INTO client_report (client_id, action, report_ip, report_date) VALUES (:id, :action, :ip, NOW())");
                $logStmt->execute([
                    'id' => $this->user['client_id'],
                    'action' => 'API Key changed',
                    'ip' => $_SERVER['REMOTE_ADDR']
                ]);
                $success = 'API key regenerated: ' . $newApiKey;
                $this->user['apikey'] = $newApiKey;
            } else {
                $error = 'Failed to regenerate API key.';
            }
        }

        // Handle timezone update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_timezone'])) {
            $timezone = doubleval($_POST['timezone']);
            $stmt = $this->db->prepare("UPDATE clients SET timezone = :tz WHERE client_id = :id");
            if ($stmt->execute(['tz' => $timezone, 'id' => $this->user['client_id']])) {
                $success = 'Timezone updated successfully!';
                $this->user['timezone'] = $timezone;
            }
        }

        // Pass user data to view
        $user = $this->user;
        
        require_once '../views/account.php';
    }
}
