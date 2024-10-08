<?php
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $nome_quadro = $_POST['nome_quadro'];

    if (!empty($nome_quadro)) {
        $stmt = $pdo->prepare("INSERT INTO quadros (nome) VALUES (:nome_quadro)");
        $stmt->bindParam(':nome_quadro', $nome_quadro);
        
        if ($stmt->execute()) {
           
            header("Location: quadros.php");
            exit(); 
        } else {
            echo "Erro ao criar quadro.";
        }        
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $quadro_id = $_POST['quadro_id'];

    if (!empty($quadro_id)) {
        
        $pdo->beginTransaction();
        
        try {
            $stmt1 = $pdo->prepare("DELETE FROM cartoes WHERE lista_id IN (SELECT id FROM listas WHERE quadro_id = :quadro_id)");
            $stmt1->bindParam(':quadro_id', $quadro_id);
            $stmt1->execute();

            $stmt2 = $pdo->prepare("DELETE FROM listas WHERE quadro_id = :quadro_id");
            $stmt2->bindParam(':quadro_id', $quadro_id);
            $stmt2->execute();

            $stmt3 = $pdo->prepare("DELETE FROM quadros WHERE id = :quadro_id");
            $stmt3->bindParam(':quadro_id', $quadro_id);
            $stmt3->execute();

            $pdo->commit();
            
            header("Location: quadros.php");
            exit(); 
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Erro ao remover quadro: " . $e->getMessage();
        }
    }
}
