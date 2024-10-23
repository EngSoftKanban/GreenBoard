<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Quadro {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM quadros");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecent() {
        $stmt = $this->pdo->query("SELECT * FROM quadros ORDER BY ultimo_acesso DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nome) {
        $stmt = $this->pdo->prepare("INSERT INTO quadros (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
    }

	public function updateAcesso($quadro_id) {
		$sqlUpdateAcesso = "UPDATE quadros SET ultimo_acesso = NOW() WHERE id = :quadro_id";
		$stmtUpdate = $pdo->prepare($sqlUpdateAcesso);
		$stmtUpdate->bindParam(':quadro_id', $quadro_id);
		$stmtUpdate->execute();
	}

    public function deleteQuadro($quadro_id) {
        
        $this->pdo->beginTransaction();

        try {
            
            $stmt1 = $this->pdo->prepare("DELETE FROM cartoes WHERE lista_id IN (SELECT id FROM listas WHERE quadro_id = :quadro_id)");
            $stmt1->bindParam(':quadro_id', $quadro_id);
            $stmt1->execute();

            
            $stmt2 = $this->pdo->prepare("DELETE FROM listas WHERE quadro_id = :quadro_id");
            $stmt2->bindParam(':quadro_id', $quadro_id);
            $stmt2->execute();

            
            $stmt3 = $this->pdo->prepare("DELETE FROM quadros WHERE id = :quadro_id");
            $stmt3->bindParam(':quadro_id', $quadro_id);
            $stmt3->execute();

        
            $this->pdo->commit();
        } catch (Exception $e) {
        
            $this->pdo->rollBack();
            throw new Exception("Erro ao remover quadro: " . $e->getMessage());
        }
    }
}
