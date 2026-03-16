<?php
require_once 'models/signalement.php';
require_once 'config/db.php';

class SignalementDAO {
    private $conn;

    public function __construct() {
        $this->conn = dbconnexion::getConnexion();
    }

    // Insérer un signalement
    public function envoyerSignalement(Signalement $signalement) {
        try {
            $sql = "INSERT INTO signalement (id_utilisateur, id_questionnaire, id_question, message) 
                    VALUES (:idUtilisateur, :idQuestionnaire, :idQuestion, :message)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':idUtilisateur',   $signalement->getIdUtilisateur());
            $stmt->bindValue(':idQuestionnaire', $signalement->getIdQuestionnaire());
            $stmt->bindValue(':idQuestion',      $signalement->getIdQuestion());
            $stmt->bindValue(':message',         $signalement->getMessage());

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erreur DAO envoyerSignalement : " . $e->getMessage());
            return false;
        }
    }

    // Récupérer tous les signalements (utile pour un admin)
    public function getTousLesSignalements() {
        try {
            $sql = "SELECT s.*, u.login, q.libelle AS libelle_question, qn.nom AS nom_questionnaire
                    FROM signalement s
                    JOIN utilisateurs u    ON s.id_utilisateur   = u.id
                    JOIN question q        ON s.id_question      = q.id
                    JOIN questionnaire qn  ON s.id_questionnaire = qn.id
                    ORDER BY s.date_envoi DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $signalements = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $s = new Signalement();
                $s->setId($row['id']);
                $s->setIdUtilisateur($row['id_utilisateur']);
                $s->setIdQuestionnaire($row['id_questionnaire']);
                $s->setIdQuestion($row['id_question']);
                $s->setMessage($row['message']);
                $s->setDateEnvoi($row['date_envoi']);
                $signalements[] = $s;
            }
            return $signalements;

        } catch (PDOException $e) {
            error_log("Erreur DAO getTousLesSignalements : " . $e->getMessage());
            return [];
        }
    }
}
?>