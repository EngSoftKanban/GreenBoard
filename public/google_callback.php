<?php
require_once 'src/bdpdo.php';
require_once 'src/Model/User.php';
require_once 'src/Controller/LoginController.php';
require_once 'vendor/autoload.php';

use EngSoftKanban\GreenBoard\Controller\LoginController;

$client = new Google_Client();
$client->setClientId('839888767080-06qndqiukr23tt6tcddg5mak4h4dghq3.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google_callback.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Verifica se houve erro na resposta do token
    if (isset($token['error'])) {
        die("Erro ao obter o token de acesso: " . htmlspecialchars($token['error_description']));
    }

    // Procede se o 'access_token' está presente
    if (isset($token['access_token'])) {
        $client->setAccessToken($token['access_token']);
        
        $oauth = new Google_Service_Oauth2($client);
        $googleUser = $oauth->userinfo->get();
        
        $controller = new LoginController($pdo);
        $usuario = $controller->findUserByEmail($googleUser->email);

        if ($usuario) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['icone'] = $usuario['icone'];
        } else {
            // Cria o usuário no banco de dados se não existir
            $controller->createUser([
                'nome' => $googleUser->name,
                'email' => $googleUser->email,
                'senha' => null, // OAuth users não têm senha
                'icone' => $googleUser->picture,
            ]);
        }

        header("Location: painel.php");
        exit();
    } else {
        die("Erro: access_token não encontrado na resposta.");
    }
}

