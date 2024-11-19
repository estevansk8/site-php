<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Jogador.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar jogadores
if (method("GET")) {
    try {
        if (valid($_GET, ["id"])) {
            if (!Jogador::exist($_GET["id"])) {
                throw new Exception("Jogador não encontrado", 400);
            }
        }
        
        $lista = Jogador::listar();
        output(200, $lista);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Cadastrar jogador
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (count($data) > 4) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!valid($data, ["nome", "posicao", "time_id"])) {
            throw new Exception("Nome, posição ou time_id não informado", 404);
        }

        $res = Jogador::insert($data["nome"], $data["posicao"], $data["time_id"], $data["created_by"] ?? null);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar o jogador", 500);
        }
        output(200, ["msg" => "Jogador criado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar jogador
if (method("PUT")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 404);
        }
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (count($data) > 3) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!isset($data["nome"]) && !isset($data["posicao"]) && !isset($data["time_id"])) {
            throw new Exception("Nenhum campo válido para atualizar foi enviado", 400);
        }

        if (!Jogador::exist($_GET["id"])) {
            throw new Exception("Jogador não encontrado", 400);
        }

        $res = Jogador::update($_GET["id"], $data["nome"], $data["posicao"], $data["time_id"]);
        if (!$res) {
            throw new Exception("Não foi possível atualizar o jogador", 500);
        }
        output(200, ["msg" => "Jogador atualizado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar jogador
if (method("DELETE")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 404);
        }

        if (!Jogador::exist($_GET["id"])) {
            throw new Exception("Jogador não encontrado", 400);
        }

        $res = Jogador::deletar($_GET["id"]);
        if (!$res) {
            throw new Exception("Não foi possível deletar o jogador", 500);
        }
        output(200, ["msg" => "Jogador deletado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

?>
