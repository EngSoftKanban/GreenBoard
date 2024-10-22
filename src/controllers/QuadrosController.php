<?php
require_once 'models/Quadro.php';

class QuadrosController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

    public function index() {
        $quadros = $this->quadroModel->getAll();
        $recentes = $this->quadroModel->getRecent();
        include 'views/quadros.php';
    }

    public function delete($id) {
        
        $this->quadroModel->deleteAllRelated($id); 
        header("Location: view/quadros.php");
        exit();
    }

    public function create($nome) {
        $this->quadroModel->create($nome);
        header("Location: view/quadros.php");
        exit();
    }
}
