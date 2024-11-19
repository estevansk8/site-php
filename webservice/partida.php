<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Partida.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar partidas
// Listar partidas
if (method("GET")) {
    try {
        if (valid($_GET, ["id"])) {
            // Se um ID for passado, buscar partidas por campeonato
            $lista = Partida::listar($_GET["id"]);
        } else {
            // Se nenhum ID for passado, listar todas as partidas
            $lista = Partida::listar();
        }

        output(200, $lista);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Cadastrar partida
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (count($data) > 7) { // Atualizado para os novos campos
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }
        if (!valid($data, ["data", "local", "campeonato_id", "time_home_id", "time_away_id", "time_home_gols", "time_away_gols"])) {
            throw new Exception("Informações incompletas", 404);
        }

        $res = Partida::insert($data["data"], $data["local"], $data["campeonato_id"], $data["time_home_id"], $data["time_away_id"], $data["time_home_gols"], $data["time_away_gols"], $data["created_by"] ?? null);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar a partida", 500);
        }
        output(200, ["msg" => "Partida criada com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar partida
if (method("PUT")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 404);
        }
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (count($data) > 7) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }

        if (!Partida::exist($_GET["id"])) {
            throw new Exception("Partida não encontrada", 400);
        }

        $res = Partida::update(
            $_GET["id"],
            $data["data"] ?? null,
            $data["local"] ?? null,
            $data["campeonato_id"] ?? null,
            $data["time_home_id"] ?? null,
            $data["time_away_id"] ?? null,
            $data["time_home_gols"] ?? null,
            $data["time_away_gols"] ?? null
        );
        if (!$res) {
            throw new Exception("Não foi possível atualizar a partida", 500);
        }

        output(200, ["msg" => "Partida atualizada com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar partida
if (method("DELETE")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 404);
        }

        if (!Partida::exist($_GET["id"])) {
            throw new Exception("Partida não encontrada", 400);
        }

        $res = Partida::deletar($_GET["id"]);
        if (!$res) {
            throw new Exception("Não foi possível deletar a partida", 500);
        }

        output(200, ["msg" => "Partida deletada com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Verificar o vencedor do campeonato
if (method("GET") && isset($_GET['vencedorCampeonato'])) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID do campeonato não enviado", 404);
        }

        $campeonato_id = $_GET["id"];
        $vencedor_id = Partida::vencedorCampeonato($campeonato_id);

        if (!$vencedor_id) {
            throw new Exception("Nenhum vencedor encontrado para este campeonato", 404);
        }

        $conexao = Conexao::getConexao();
        $sql = $conexao->prepare("SELECT * FROM Clube WHERE id = ?");
        $sql->execute([$vencedor_id]);
        $vencedor = $sql->fetch(PDO::FETCH_ASSOC);

        output(200, ["msg" => "O vencedor do campeonato é o time", "vencedor" => $vencedor]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}
