<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/TimeCampeonato.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar todos os registros
if (method("GET")) {
    try {
        $lista = TimeCampeonato::findAll();
        output(200, $lista);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Adicionar time ao campeonato
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (count($data) > 2) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!valid($data, ["time_id", "campeonato_id"])) {
            throw new Exception("time_id e campeonato_id não informados", 404);
        }

        // Verificações
        if (!TimeCampeonato::existTime($data["time_id"])) {
            throw new Exception("Time não encontrado", 404);
        }
        if (!TimeCampeonato::existCampeonato($data["campeonato_id"])) {
            throw new Exception("Campeonato não encontrado", 404);
        }
        if (TimeCampeonato::isTimeRegisteredInCampeonato($data["time_id"], $data["campeonato_id"])) {
            throw new Exception("Time já cadastrado no campeonato", 400);
        }

        $res = TimeCampeonato::insert($data["time_id"], $data["campeonato_id"]);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar o time no campeonato", 500);
        }
        output(200, ["msg" => "Time adicionado ao campeonato com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar time no campeonato
if (method("PUT")) {
    try {
        output(400, ["msg" => "Esse metodo não é permitido aqui"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar time do campeonato
if (method("DELETE")) {
    try {
        output(400, ["msg" => "Esse metodo não é permitido aqui"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

?>
