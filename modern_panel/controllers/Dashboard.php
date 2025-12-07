<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class Dashboard {
    private $db;

    public function __construct() {
        Session::init();
        if (!Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $user_id = Session::get('user_id');

        // Fetch User Data
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE client_id = :id");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch();

        // Fetch Stats
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE client_id = :id");
        $stmt->execute(['id' => $user_id]);
        $total_orders = $stmt->fetch()['total_orders'];

        $stmt = $this->db->prepare("SELECT COUNT(*) as pending_orders FROM orders WHERE client_id = :id AND order_status = 'pending'");
        $stmt->execute(['id' => $user_id]);
        $pending_orders = $stmt->fetch()['pending_orders'];

        require_once '../views/dashboard.php';
    }
}
