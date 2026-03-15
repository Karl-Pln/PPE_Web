<?php
require_once "config/db.php";
require_once "models/questionnaire.php";

class QuestionnaireDAO {

    public function getAll(): array {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->query("
                SELECT q.id, q.nom, q.theme_id, q.createur_id,
                       t.libelle AS theme_libelle,
                       COUNT(qu.id) AS nb_questions
                FROM questionnaire q
                JOIN theme t ON t.id = q.theme_id
                LEFT JOIN question qu ON qu.questionnaire_id = q.id
                GROUP BY q.id, q.nom, q.theme_id, q.createur_id, t.libelle
                ORDER BY q.nom
            ");
            $liste = [];
            foreach ($stmt->fetchAll() as $row) {
                $q               = new Questionnaire();
                $q->id           = $row['id'];
                $q->nom          = $row['nom'];
                $q->themeId      = $row['theme_id'];
                $q->themeLibelle = $row['theme_libelle'];
                $q->createurId   = $row['createur_id'];
                $q->nbQuestions  = $row['nb_questions'];
                $liste[]         = $q;
            }
            return $liste;
        } catch (Exception $e) {
            error_log("DAO getAll questionnaires : " . $e->getMessage());
        }
        return [];
    }

    public function getById(int $id): ?Questionnaire {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("
                SELECT q.*, t.libelle AS theme_libelle
                FROM questionnaire q
                JOIN theme t ON t.id = q.theme_id
                WHERE q.id = :id
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) {
                $q               = new Questionnaire();
                $q->id           = $row['id'];
                $q->nom          = $row['nom'];
                $q->themeId      = $row['theme_id'];
                $q->themeLibelle = $row['theme_libelle'];
                $q->createurId   = $row['createur_id'];
                return $q;
            }
        } catch (Exception $e) {
            error_log("DAO getById questionnaire : " . $e->getMessage());
        }
        return null;
    }

    public function ajouter(Questionnaire $q): int {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO questionnaire (nom, theme_id, createur_id) VALUES (:nom, :theme_id, :createur_id)");
            $stmt->execute([
                ':nom'         => $q->nom,
                ':theme_id'    => $q->themeId,
                ':createur_id' => $q->createurId
            ]);
            return (int) $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("DAO ajouter questionnaire : " . $e->getMessage());
        }
        return -1;
    }

    public function modifier(Questionnaire $q): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("UPDATE questionnaire SET nom = :nom, theme_id = :theme_id WHERE id = :id");
            $stmt->execute([':nom' => $q->nom, ':theme_id' => $q->themeId, ':id' => $q->id]);
            return true;
        } catch (Exception $e) {
            error_log("DAO modifier questionnaire : " . $e->getMessage());
        }
        return false;
    }

    public function supprimer(int $id): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM questionnaire WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log("DAO supprimer questionnaire : " . $e->getMessage());
        }
        return false;
    }

    public function getThemes(): array {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->query("SELECT * FROM theme ORDER BY libelle");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("DAO getThemes : " . $e->getMessage());
        }
        return [];
    }
}