<?php
namespace EngSoftKanban\GreenBoard\Controller;

use EngSoftKanban\GreenBoard\Model\PasswordModel;

class PasswordController {
    private $passwordModel;

    public function __construct($pdo) {
        $this->passwordModel = new PasswordModel($pdo);
    }

    public function requestReset($email) {
        if ($this->passwordModel->isUserExists($email)) {
            $token = $this->passwordModel->generateTokenSession();
            $this->passwordModel->sendResetEmail($email, $token);
            return "E-mail de recuperação enviado!";
        } else {
            return "E-mail não encontrado!"; // Isso será retornado se o e-mail não existir
        }
    }

    // Adicione outros métodos conforme necessário
}
?>
