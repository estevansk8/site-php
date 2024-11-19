<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Campeonato.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar campeonatos
if (method("GET")) {
    try {
        if (valid($_GET, ["id"])) {
            if (!Campeonato::exist($_GET["id"])) {
                throw new Exception("Campeonato não encontrado", 400);
            }

            $campeonato = Campeonato::getById($_GET["id"]);
            output(200, $campeonato);
        } else {
            $lista = Campeonato::listar();
            output(200, $lista);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Cadastrar campeonato
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if(count($data) > 2) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!valid($data, ["nome", "ano"])) {
            throw new Exception("Nome e/ou ano não informados", 404);
        }

        $res = Campeonato::insert($data["nome"], $data["ano"]);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar o campeonato", 500);
        }
        output(200, ["msg" => "Campeonato criado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar campeonato
if (method("PUT")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 404);
        }
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }

        if (count($data) > 2) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }

        if (!isset($data["nome"]) && !isset($data["ano"])) {
            throw new Exception("Nenhum campo valido para atualizar foi enviado", 400);
        }

        if (!Campeonato::exist($_GET["id"])) {
            throw new Exception("Campeonato não encontrado", 400);
        }

        // Buscar o campeonato atual
        $campeonatoAtual = Campeonato::getById($_GET["id"]);
        if (!$campeonatoAtual) {
            throw new Exception("Campeonato não encontrado", 404);
        }

        // Usar os valores atuais se os novos não forem fornecidos
        $nome = isset($data["nome"]) ? $data["nome"] : $campeonatoAtual["nome"];
        $ano = isset($data["ano"]) ? $data["ano"] : $campeonatoAtual["ano"];

        // Atualizar o campeonato
        $res = Campeonato::update($_GET["id"], $nome, $ano);
        if (!$res) {
            throw new Exception("Não foi possível atualizar o campeonato", 500);
        }

        output(200, ["msg" => "Campeonato atualizado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar campeonato
if (method("DELETE")) {
    try {
        output(400, ["msg" => "Campeonato não é possivel ser deletado"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

?>
