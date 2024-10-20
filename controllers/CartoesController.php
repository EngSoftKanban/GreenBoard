<?php
require_once '../models/Cartao.php';

class CartaoController {
    private $cartaoModel;

    public function __construct($pdo) {
        $this->cartaoModel = new Cartao($pdo);
    }

    public function listarCartoesPorLista($listaId) {
        return $this->cartaoModel->listarPorLista($listaId);
    }

    public function adicionar($corpo, $lista_id) {
        if (empty($corpo) || empty($lista_id)) {
            return json_encode(["success" => false, "message" => "Corpo ou ID da lista não podem estar vazios."]);
        }
        $resultado = $this->cartaoModel->adicionarCartao($corpo, $lista_id);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão adicionado com sucesso!" : "Erro ao adicionar o cartão."]);
    }

    public function atualizar($id, $corpo) {
        if (empty($id) || empty($corpo)) {
            return json_encode(["success" => false, "message" => "ID ou corpo do cartão não podem estar vazios."]);
        }
        $resultado = $this->cartaoModel->atualizarCartao($id, $corpo);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão atualizado com sucesso!" : "Erro ao atualizar o cartão."]);
    }

    public function deletar($id) {
        $cartao = $this->cartaoModel->getCartaoById($id);
        
        if (!$cartao) {
            return false; // Retorna false se o cartão não for encontrado
        }
    
        $lista_id = $cartao['lista_id'];
        $posicao_cartao = $cartao['posicao'];
    
        if ($this->cartaoModel->deletarCartao($id)) {
            $this->cartaoModel->atualizarPosicoes($lista_id, $posicao_cartao);
            return true; // Retorna true se a exclusão for bem-sucedida
        } else {
            return false; // Retorna false se a exclusão falhar
        }
    }

    public function atualizarPosicoes($cartoes) {
        // Chama o método do modelo para atualizar as posições
        if (empty($cartoes)) {
            return json_encode(["success" => false, "message" => "Nenhum cartão fornecido."]);
        }

        try {
            $resultado = $this->cartaoModel->atualizarPosicoes($cartoes);
            return json_encode(["success" => true, "message" => "Posições dos cartões atualizadas com sucesso."]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
        }
    }
}
