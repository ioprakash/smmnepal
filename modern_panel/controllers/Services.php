<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class Services {
    private $db;

    public function __construct() {
        Session::init();
        // Services page might be public, but let's keep it consistent for now
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        // Fetch Services with Category Name
        $stmt = $this->db->prepare("SELECT s.*, c.category_name FROM services s LEFT JOIN categories c ON s.category_id = c.category_id WHERE s.service_deleted = '0' ORDER BY s.service_line ASC");
        $stmt->execute();
        $services = $stmt->fetchAll();

        require_once '../views/services.php';
    }
}
