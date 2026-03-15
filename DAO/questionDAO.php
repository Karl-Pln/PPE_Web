<?php
require_once "config/db.php";
require_once "models/question.php";

class QuestionDAO {

    public function getByQuestionnaire(int $questionnaireId): array {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM question WHERE questionnaire_id = :qid ORDER BY ordre, id");
            $stmt->execute([':qid' => $questionnaireId]);
            $liste = [];
            foreach ($stmt->fetchAll() as $row) {
                $q                  = new Question();
                $q->id              = $row['id'];
                $q->questionnaireId = $row['questionnaire_id'];
                $q->libelle         = $row['libelle'];
                $q->typeReponse     = $row['type_reponse'];
                $q->bonneReponse    = $row['bonne_reponse'];
                $q->ordre           = $row['ordre'];
                $liste[]            = $q;
            }
            return $liste;
        } catch (Exception $e) {
            error_log("DAO getByQuestionnaire : " . $e->getMessage());
        }
        return [];
    }

    public function ajouter(Question $q): int {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                INSERT INTO question (questionnaire_id, libelle, type_reponse, bonne_reponse, ordre)
                VALUES (:qid, :libelle, :type, :bonneReponse, :ordre)
            ");
            $stmt->execute([
                ':qid'          => $q->questionnaireId,
                ':libelle'      => $q->libelle,
                ':type'         => $q->typeReponse,
                ':bonneReponse' => $q->bonneReponse,
                ':ordre'        => $q->ordre
            ]);
            return (int) $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("DAO ajouter question : " . $e->getMessage());
        }
        return -1;
    }

    public function supprimer(int $id): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM question WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log("DAO supprimer question : " . $e->getMessage());
        }
        return false;
    }

    public function modifier(Question $q): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                UPDATE question 
                SET libelle = :libelle, type_reponse = :type, 
                    bonne_reponse = :bonneReponse, ordre = :ordre 
                WHERE id = :id
            ");
            $stmt->execute([
                ':libelle'      => $q->libelle,
                ':type'         => $q->typeReponse,
                ':bonneReponse' => $q->bonneReponse,
                ':ordre'        => $q->ordre,
                ':id'           => $q->id
            ]);
            return true;
        } catch (Exception $e) {
            error_log("DAO modifier question : " . $e->getMessage());
        }
        return false;
    }
}