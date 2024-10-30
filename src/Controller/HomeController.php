<?php
namespace Src\Controller;

class HomeController {
    public function index() {
        // Renderiza a view da página inicial
        require_once __DIR__ . '/../View/home.php';
    }
}
