<?php

require_once(__DIR__ . "/../configs/Database.php");
require_once(__DIR__ . "/../configs/utils.php");

class TimeCampeonato {
    // Adicionar time ao campeonato
    public static function insert($time_id, $campeonato_id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO TimeCampeonato (time_id, campeonato_id) VALUES (?, ?)");
            $result = $sql->execute([$time_id, $campeonato_id]);

            return $result ? true : false;
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Encontrar todos os registros de time no campeonato
    public static function findAll() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM TimeCampeonato");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os registros
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Verificar se o time existe
    public static function existTime($time_id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Clube WHERE id = ?");
            $sql->execute([$time_id]);
            return $sql->fetchColumn() > 0; // Retorna true se o time existir
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Verificar se o campeonato existe
    public static function existCampeonato($campeonato_id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Campeonato WHERE id = ?");
            $sql->execute([$campeonato_id]);
            return $sql->fetchColumn() > 0; // Retorna true se o campeonato existir
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Verificar se o time já está cadastrado no campeonato
    public static function isTimeRegisteredInCampeonato($time_id, $campeonato_id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM TimeCampeonato WHERE time_id = ? AND campeonato_id = ?");
            $sql->execute([$time_id, $campeonato_id]);
            return $sql->fetchColumn() > 0; // Retorna true se o time já estiver registrado
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }


    // Verificar se o registro existe
    public static function exist($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM TimeCampeonato WHERE id = ?");
            $sql->execute([$id]);
            return $sql->fetchColumn() > 0; // Retorna true se o registro existir
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }


}
?>
