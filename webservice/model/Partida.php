<?php

require_once(__DIR__ . "/../configs/Database.php");

class Partida
{
    public static function listar($campeonato_id = null) {
        try {
            $conexao = Conexao::getConexao();
            
            // Se um ID de campeonato for passado, filtrar as partidas por esse campeonato
            if ($campeonato_id) {
                $sql = $conexao->prepare("SELECT * FROM Partida WHERE campeonato_id = ?");
                $sql->execute([$campeonato_id]);
            } else {
                $sql = $conexao->prepare("SELECT * FROM Partida");
                $sql->execute();
            }
    
            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function insert($data, $local, $campeonato_id, $time_home_id, $time_away_id, $time_home_gols, $time_away_gols, $created_by = null) {
        try {
            // Verificar se o campeonato existe
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Campeonato WHERE id = ?");
            $sql->execute([$campeonato_id]);
            $campeonatoExiste = $sql->fetchColumn();
    
            if ($campeonatoExiste == 0) {
                throw new Exception("Campeonato não encontrado", 400);
            }
    
            // Verificar se os IDs dos times não são iguais
            if ($time_home_id == $time_away_id) {
                throw new Exception("Os IDs dos times não podem ser iguais", 400);
            }
    
            // Verificar se ambos os times estão vinculados ao campeonato na tabela TimeCampeonato
            $sql = $conexao->prepare("SELECT COUNT(*) FROM TimeCampeonato WHERE campeonato_id = ? AND time_id IN (?, ?)");
            $sql->execute([$campeonato_id, $time_home_id, $time_away_id]);
            $timesVinculados = $sql->fetchColumn();
    
            if ($timesVinculados < 2) {
                throw new Exception("Um ou ambos os times não estão vinculados ao campeonato", 400);
            }
    
            // Inserir a partida
            $sql = $conexao->prepare("INSERT INTO Partida (data, local, campeonato_id, time_home_id, time_away_id, time_home_gols, time_away_gols, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$data, $local, $campeonato_id, $time_home_id, $time_away_id, $time_home_gols, $time_away_gols, $created_by]);
    
            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function update($id, $data = null, $local = null, $campeonato_id = null, $time_home_id = null, $time_away_id = null, $time_home_gols = null, $time_away_gols = null) {
        try {
            $conexao = Conexao::getConexao();
            
            $fields = [];
            $params = [];
    
            if ($data !== null) {
                $fields[] = "data = ?";
                $params[] = $data;
            }
            if ($local !== null) {
                $fields[] = "local = ?";
                $params[] = $local;
            }
            if ($campeonato_id !== null) {
                $fields[] = "campeonato_id = ?";
                $params[] = $campeonato_id;
            }
            if ($time_home_id !== null) {
                $fields[] = "time_home_id = ?";
                $params[] = $time_home_id;
            }
            if ($time_away_id !== null) {
                $fields[] = "time_away_id = ?";
                $params[] = $time_away_id;
            }
            if ($time_home_gols !== null) {
                $fields[] = "time_home_gols = ?";
                $params[] = $time_home_gols;
            }
            if ($time_away_gols !== null) {
                $fields[] = "time_away_gols = ?";
                $params[] = $time_away_gols;
            }
    
            if (empty($fields)) {
                throw new Exception("Nenhum campo válido para atualizar", 400);
            }
    
            $params[] = $id;
    
            $sql = $conexao->prepare("UPDATE Partida SET " . implode(", ", $fields) . " WHERE id = ?");
            $sql->execute($params);
    
            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function deletar($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE FROM Partida WHERE id = ?");
            $sql->execute([$id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function exist($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Partida WHERE id = ?");
            $sql->execute([$id]);

            return $sql->fetchColumn();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function vencedorCampeonato($campeonato_id) {
        try {
            $conexao = Conexao::getConexao();
            
            // Buscar todas as partidas do campeonato com o campo vencedor preenchido
            $sql = $conexao->prepare("SELECT vencedor FROM Partida WHERE campeonato_id = ? AND vencedor IS NOT NULL");
            $sql->execute([$campeonato_id]);
            $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($resultados)) {
                throw new Exception("Nenhuma partida com vencedor encontrada neste campeonato", 404);
            }

            // Contabilizar o número de vitórias de cada time
            $vitorias = [];
            foreach ($resultados as $partida) {
                $vencedor_id = $partida['vencedor'];
                if (isset($vitorias[$vencedor_id])) {
                    $vitorias[$vencedor_id]++;
                } else {
                    $vitorias[$vencedor_id] = 1;
                }
            }

            // Determinar o time com maior número de vitórias
            $time_vencedor = array_keys($vitorias, max($vitorias))[0];

            // Retornar o ID do time vencedor
            return $time_vencedor;
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}
