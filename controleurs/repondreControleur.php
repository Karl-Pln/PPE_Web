<?php
require_once "dao/questionnaireDAO.php";
require_once "dao/questionDAO.php";
require_once "dao/reponsePossibleDAO.php";
require_once "dao/scoreDAO.php";
require_once "models/score.php";

class RepondreControleur {

    private QuestionnaireDAO   $questionnaireDAO;
    private QuestionDAO        $questionDAO;
    private ReponsePossibleDAO $reponsePossibleDAO;
    private ScoreDAO           $scoreDAO;

    public function __construct() {
        $this->questionnaireDAO   = new QuestionnaireDAO();
        $this->questionDAO        = new QuestionDAO();
        $this->reponsePossibleDAO = new ReponsePossibleDAO();
        $this->scoreDAO           = new ScoreDAO();
    }

    public function afficher($questionnaireId) {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $questionnaire = $this->questionnaireDAO->getById((int) $questionnaireId);
        if (!$questionnaire) {
            header('Location: index.php?action=liste_questionnaires');
            exit;
        }

        $questions = $this->questionDAO->getByQuestionnaire((int) $questionnaireId);
        foreach ($questions as $q) {
            if ($q->typeReponse === 'ListeValeurs') {
                $q->reponsesPossibles = $this->reponsePossibleDAO->getByQuestion($q->id);
            }
        }

        $utilisateur = $_SESSION['utilisateur'];
        include "vues/detailQuestionnaire.php";
    }

    public function soumettre() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $utilisateur     = $_SESSION['utilisateur'];
        $questionnaireId = (int) ($_POST['questionnaire_id'] ?? 0);
        $questionnaire   = $this->questionnaireDAO->getById($questionnaireId);
        $questions       = $this->questionDAO->getByQuestionnaire($questionnaireId);

        foreach ($questions as $q) {
            if ($q->typeReponse === 'ListeValeurs') {
                $q->reponsesPossibles = $this->reponsePossibleDAO->getByQuestion($q->id);
            }
        }

        $score   = 0;
        $total   = count($questions);
        $details = [];

        foreach ($questions as $q) {
            $reponseUtilisateur = $_POST['reponse_' . $q->id] ?? null;
            $estCorrecte        = false;
            $reponseTexte       = '—';
            $bonneTexte         = '—';

            if ($q->typeReponse === 'VraiFaux') {
                $estCorrecte  = $reponseUtilisateur === $q->bonneReponse;
                $reponseTexte = $reponseUtilisateur ?? '—';
                $bonneTexte   = $q->bonneReponse;
            } else {
                foreach ($q->reponsesPossibles as $r) {
                    if ($r->id == $reponseUtilisateur) $reponseTexte = $r->libelle;
                    if ($r->estCorrecte)                $bonneTexte   = $r->libelle;
                    if ($r->id == $reponseUtilisateur && $r->estCorrecte) $estCorrecte = true;
                }
            }

            if ($estCorrecte) $score++;

            $details[] = [
                'libelle'     => $q->libelle,
                'reponse'     => $reponseTexte,
                'bonne'       => $bonneTexte,
                'estCorrecte' => $estCorrecte
            ];
        }

        $s                  = new Score();
        $s->utilisateurId   = $utilisateur->id;
        $s->questionnaireId = $questionnaireId;
        $s->scoreObtenu     = $score;
        $s->scoreMax        = $total;
        $s->datePassage     = date('Y-m-d H:i:s');
        $this->scoreDAO->enregistrer($s);

        include "vues/resultat.php";
    }
}