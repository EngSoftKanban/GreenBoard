<?php
namespace EngSoftKanban\GreenBoard\Controller;

class HomeController {
    public function index() {
        // Renderiza a view da página inicial
        require_once 'src/View/home.php';
    }
}
