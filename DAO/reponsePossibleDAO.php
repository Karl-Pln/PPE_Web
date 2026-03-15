<?php
require_once "config/db.php";
require_once "models/reponsePossible.php";

class ReponsePossibleDAO {

    public function getByQuestion(int $questionId): array {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM reponse_possible WHERE question_id = :qid");
            $stmt->execute([':qid' => $questionId]);
            $liste = [];
            foreach ($stmt->fetchAll() as $row) {
                $r              = new ReponsePossible();
                $r->id          = $row['id'];
                $r->questionId  = $row['question_id'];
                $r->libelle     = $row['libelle'];
                $r->estCorrecte = (bool) $row['est_correcte'];
                $r->poids       = $row['poids'];
                $liste[]        = $r;
            }
            return $liste;
        } catch (Exception $e) {
            error_log("DAO getByQuestion : " . $e->getMessage());
        }
        return [];
    }

    public function ajouter(ReponsePossible $r): int {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                INSERT INTO reponse_possible (question_id, libelle, est_correcte, poids)
                VALUES (:qid, :libelle, :correct, :poids)
            ");
            $stmt->execute([
                ':qid'     => $r->questionId,
                ':libelle' => $r->libelle,
                ':correct' => $r->estCorrecte ? 1 : 0,
                ':poids'   => $r->poids
            ]);
            return (int) $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("DAO ajouter réponse : " . $e->getMessage());
        }
        return -1;
    }

    public function supprimerParQuestion(int $questionId): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM reponse_possible WHERE question_id = :qid");
            $stmt->execute([':qid' => $questionId]);
            return true;
        } catch (Exception $e) {
            error_log("DAO supprimerParQuestion : " . $e->getMessage());
        }
        return false;
    }
}