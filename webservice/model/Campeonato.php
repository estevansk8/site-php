<?php

require_once(__DIR__ . "/../configs/Database.php");
require_once(__DIR__ . "/../configs/utils.php");

class Campeonato {

    // Listar todos os campeonatos
    public static function listar() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM Campeonato");
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function listarPorTime($time_id) {
        try {
            $conexao = Conexao::getConexao();
            
            // Query para listar todos os campeonatos em que o time está inscrito
            $sql = $conexao->prepare("
                SELECT c.* 
                FROM Campeonato c
                INNER JOIN TimeCampeonato tc ON c.id = tc.campeonato_id
                WHERE tc.time_id = ?
            ");
            $sql->execute([$time_id]);
            
            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Inserir novo campeonato
    public static function insert($nome, $ano) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO Campeonato (nome, ano) VALUES (?, ?)");
            $sql->execute([$nome, $ano]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Buscar campeonato por ID
    public static function getById($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM Campeonato WHERE id = ?");
            $sql->execute([$id]);

            return $sql->fetch();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Verificar se campeonato existe
    public static function exist($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Campeonato WHERE id = ?");
            $sql->execute([$id]);

            return $sql->fetchColumn();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Atualizar campeonato
    public static function update($id, $nome, $ano) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("UPDATE Campeonato SET nome = ?, ano = ? WHERE id = ?");
            $sql->execute([$nome, $ano, $id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Deletar campeonato
    public static function deletar($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE FROM Campeonato WHERE id = ?");
            $sql->execute([$id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}