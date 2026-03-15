<?php
require_once "config/db.php";
require_once "models/score.php";

class ScoreDAO {

    public function enregistrer(Score $s): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                INSERT INTO score (utilisateur_id, questionnaire_id, score, score_max, date_passage)
                VALUES (:uid, :qid, :score, :scoreMax, :date)
            ");
            $stmt->execute([
                ':uid'      => $s->utilisateurId,
                ':qid'      => $s->questionnaireId,
                ':score'    => $s->scoreObtenu,
                ':scoreMax' => $s->scoreMax,
                ':date'     => $s->datePassage
            ]);
            return true;
        } catch (Exception $e) {
            error_log("DAO enregistrer score : " . $e->getMessage());
        }
        return false;
    }

    public function getByUtilisateur(int $utilisateurId): array {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                SELECT s.*, q.nom AS questionnaire_nom
                FROM score s
                JOIN questionnaire q ON q.id = s.questionnaire_id
                WHERE s.utilisateur_id = :uid
                ORDER BY s.date_passage DESC
            ");
            $stmt->execute([':uid' => $utilisateurId]);
            $liste = [];
            foreach ($stmt->fetchAll() as $row) {
                $s                   = new Score();
                $s->id               = $row['id'];
                $s->utilisateurId    = $utilisateurId;
                $s->questionnaireId  = $row['questionnaire_id'];
                $s->questionnaireNom = $row['questionnaire_nom'];
                $s->scoreObtenu      = $row['score'];
                $s->scoreMax         = $row['score_max'];
                $s->datePassage      = $row['date_passage'];
                $liste[]             = $s;
            }
            return $liste;
        } catch (Exception $e) {
            error_log("DAO getByUtilisateur : " . $e->getMessage());
        }
        return [];
    }
}