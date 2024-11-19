<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Partida.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Verificar o vencedor do campeonato
if (method("GET")) {
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
