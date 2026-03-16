<?php
class Signalement {
    private $id;
    private $idUtilisateur;
    private $idQuestionnaire;
    private $idQuestion;
    private $message;
    private $dateEnvoi;

    public function getId() { return $this->id; }
    public function getIdUtilisateur() { return $this->idUtilisateur; }
    public function getIdQuestionnaire() { return $this->idQuestionnaire; }
    public function getIdQuestion() { return $this->idQuestion; }
    public function getMessage() { return $this->message; }
    public function getDateEnvoi() { return $this->dateEnvoi; }

    
    public function setId($id) { $this->id = $id; }
    public function setIdUtilisateur($idUtilisateur) { $this->idUtilisateur = $idUtilisateur; }
    public function setIdQuestionnaire($idQuestionnaire) { $this->idQuestionnaire = $idQuestionnaire; }
    public function setIdQuestion($idQuestion) { $this->idQuestion = $idQuestion; }
    public function setMessage($message) { $this->message = $message; }
    public function setDateEnvoi($dateEnvoi) { $this->dateEnvoi = $dateEnvoi; }
}
?>