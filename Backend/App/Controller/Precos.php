<?php

use App\Core\Controller;

class Precos extends Controller{
    public function index()
    {
        $precoModel = $this->Model("Preco");
        $precos = $precoModel->listAll();
        echo json_encode($precos, JSON_UNESCAPED_UNICODE);
    }

    public function store(){
        $novoPreco = $this->getRequestBody();
        $precoModel = $this->Model("Preco");

        $precoModel->primeiraHora = str_replace(",", ".", $novoPreco->primeiraHora);
        $precoModel->demaisHoras = str_replace(",", ".", $novoPreco->demaisHoras);
        $erros = $this->validarCampos();
        if (count($erros) > 0) {
            http_response_code(404);
            echo json_encode($erros, JSON_UNESCAPED_UNICODE);
            exit();
        }
        $precoModel = $precoModel->insert();
        if ($precoModel) {
            http_response_code(201);
            echo json_encode($precoModel, JSON_UNESCAPED_UNICODE);
        } else {
            //se deu errado, mudar status code para 500 e retornar mensagem de erro
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir preços"]);
        }
    }

    private function validarCampos(){
        $precoModel = $this->Model("Preco");
        $erros = [];

        if (!isset($precoModel->primeiraHora) && $precoModel->primeiraHora == "") {
            $erros[] = "O campo uma hora é obrigatorio";
        } elseif (!is_numeric(str_replace(",", ".", $precoModel->primeiraHora))) {
            $erros[] = "O campo uma hora tem q ser um numero";
        }
        if (!isset($precoModel->demaisHoras) && $precoModel->demaisHoras == "") {
            $erros[] = " Demais horas é obrigatório";
        } elseif (!is_numeric(str_replace(",", ".", $precoModel->demaisHoras))) {
            $erros[] = "Demais horas deve ser um número";
        }
        return $erros;
    }
}
