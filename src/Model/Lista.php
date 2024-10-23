<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Lista {

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=" . apache_getenv("DB_HOST") . ";dbname=" . apache_getenv("DB_NAME"), 
                             apache_getenv("DB_USER"), apache_getenv("DB_PASS"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function listar($quadro_id) {
        $sql = "SELECT * FROM listas WHERE quadro_id = :quadro_id ORDER BY posicao";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':quadro_id', $quadro_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarLista($titulo, $quadro_id) {
        $sql = "INSERT INTO listas (titulo, posicao, quadro_id) VALUES (:titulo, 0, :quadro_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':quadro_id', $quadro_id);

        return $stmt->execute();  // Retorna true se bem sucedido
    }
}
