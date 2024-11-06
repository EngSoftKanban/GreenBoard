<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Quadro {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($usuario_id) {
        $stmt = $this->pdo->query("SELECT q.* FROM quadros q WHERE q.id IN (SELECT p.quadro_id FROM permissoes p WHERE p.usuario_id = $usuario_id)");
		$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecent($usuario_id) {
        $stmt = $this->pdo->query("SELECT q.* FROM quadros q WHERE q.id IN (SELECT p.quadro_id FROM permissoes p WHERE p.usuario_id = $usuario_id) ORDER BY q.acessado_em DESC LIMIT 5");
		$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nome, $usuario_id) {
		$stmt = $this->pdo->prepare("INSERT INTO quadros (nome) VALUES (:nome)");
		$stmt->bindParam(':nome', $nome);
		$stmt->execute();
		$stmt = $this->pdo->prepare("INSERT INTO permissoes (eh_dono, eh_admin, usuario_id, quadro_id) VALUES (1, 1, :usuario_id, :quadro_id)");
		$stmt->bindParam(':usuario_id', $usuario_id);
		$quadro_id = $this->pdo->lastInsertId('quadros');
		$stmt->bindParam(':quadro_id', $quadro_id);
		$stmt->execute();
    }

	public function updateAcesso($quadro_id) {
		$sqlUpdateAcesso = "UPDATE quadros SET acessado_em = NOW() WHERE id = :quadro_id";
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

            
			$stmt3 = $this->pdo->prepare("DELETE FROM permissoes WHERE quadro_id = :quadro_id");
			$stmt3->bindParam(':quadro_id', $quadro_id);
			$stmt3->execute();

			$stmt4 = $this->pdo->prepare("DELETE FROM quadros WHERE id = :quadro_id");
			$stmt4->bindParam(':quadro_id', $quadro_id);
			$stmt4->execute();

        
            $this->pdo->commit();
        } catch (Exception $e) {
        
            $this->pdo->rollBack();
            throw new Exception("Erro ao remover quadro: " . $e->getMessage());
        }
    }
}
