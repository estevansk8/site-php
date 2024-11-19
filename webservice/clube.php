<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Clube.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar clubes
if (method("GET")) {
    try {
        if (valid($_GET, ["id"])) {
            if (!Clube::exist($_GET["id"])) {
                throw new Exception("Clube não encontrado", 400);
            }
        }
        
        $lista = Clube::listar();
        output(200, $lista);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Cadastrar clube
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if(count($data) > 3) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!valid($data, ["nome"])) {
            throw new Exception("Nome não informado", 404);
        }

        $res = Clube::insert($data["nome"], $data["cidade"] ?? null, $data["estadio"] ?? null, $data["created_by"] ?? null);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar o clube", 500);
        }
        output(200, ["msg" => "Clube criado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar clube
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
        if (!isset($data["nome"]) && !isset($data["cidade"]) && !isset($data["estadio"])) {
            throw new Exception("Nenhum campo válido para atualizar foi enviado", 400);
        }

        if (!Clube::exist($_GET["id"])) {
            throw new Exception("Clube não encontrado", 400);
        }

        $res = Clube::update($_GET["id"], $data["nome"], $data["cidade"], $data["estadio"]);
        if (!$res) {
            throw new Exception("Não foi possível atualizar o clube", 500);
        }
        output(200, ["msg" => "Clube atualizado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar clube
if (method("DELETE")) {
    try {
        output(400, ["msg" => "Clube não é possivel ser deletado"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

?>
