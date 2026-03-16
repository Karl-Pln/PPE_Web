<?php
require_once 'DAO/signalementDAO.php';
require_once 'DAO/questionDAO.php';
require_once 'DAO/questionnaireDAO.php';
require_once 'models/signalement.php';

class SignalementControleur {
    private $dao;

    public function __construct() {
        $this->dao = new SignalementDAO();
    }

    // Affiche le formulaire de signalement (GET)
    public function afficher() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $idQuestion      = isset($_GET['id_question'])      ? (int)$_GET['id_question']      : 0;
        $idQuestionnaire = isset($_GET['id_questionnaire'])  ? (int)$_GET['id_questionnaire'] : 0;

        $questionDAO      = new QuestionDAO();
        $questionnaireDAO = new QuestionnaireDAO();

        $question      = $questionDAO->getById($idQuestion);
        $questionnaire = $questionnaireDAO->getById($idQuestionnaire);

        $libelleQuestion  = $question      ? $question->libelle : '';
        $nomQuestionnaire = $questionnaire ? $questionnaire->nom : '';
        $erreur           = '';
        $succes           = '';
        $messageAncien    = '';

        require 'vues/signalement.php';
    }

    // Traite l'envoi du formulaire (POST)
    public function envoyer() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $idQuestion      = isset($_POST['id_question'])      ? (int)$_POST['id_question']      : 0;
        $idQuestionnaire = isset($_POST['id_questionnaire'])  ? (int)$_POST['id_questionnaire'] : 0;
        $message         = trim($_POST['message'] ?? '');
        $messageAncien   = $message;

        $questionDAO      = new QuestionDAO();
        $questionnaireDAO = new QuestionnaireDAO();
        $question         = $questionDAO->getById($idQuestion);
        $questionnaire    = $questionnaireDAO->getById($idQuestionnaire);
        $libelleQuestion  = $question      ? $question->libelle : '';
        $nomQuestionnaire = $questionnaire ? $questionnaire->nom : '';
        $erreur           = '';
        $succes           = '';

        if (empty($message)) {
            $erreur = "Le message ne peut pas être vide.";
            require 'vues/signalement.php';
            return;
        }

        $signalement = new Signalement();
        $signalement->setIdUtilisateur($_SESSION['utilisateur']->id);
        $signalement->setIdQuestionnaire($idQuestionnaire);
        $signalement->setIdQuestion($idQuestion);
        $signalement->setMessage($message);

        $ok = $this->dao->envoyerSignalement($signalement);

        if ($ok) {
            $succes = "Signalement envoyé, merci !";
        } else {
            $erreur = "Échec de l'envoi, réessaie.";
        }

        require 'vues/signalement.php';
    }
}
?>