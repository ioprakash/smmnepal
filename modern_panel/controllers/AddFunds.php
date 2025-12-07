<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class AddFunds {
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

        // Fetch Active Payment Methods
        $stmt = $this->db->prepare("SELECT * FROM paymentmethods WHERE methodStatus = '1' ORDER BY methodPosition ASC");
        $stmt->execute();
        $methods = $stmt->fetchAll();

        require_once '../views/addfunds.php';
    }
}
