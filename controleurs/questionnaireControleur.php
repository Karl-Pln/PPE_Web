<?php
require_once "dao/questionnaireDAO.php";
require_once "dao/questionDAO.php";
require_once "dao/reponsePossibleDAO.php";
require_once "models/questionnaire.php";
require_once "models/question.php";
require_once "models/reponsePossible.php";

class QuestionnaireControleur {

    private QuestionnaireDAO   $dao;
    private QuestionDAO        $questionDAO;
    private ReponsePossibleDAO $reponsePossibleDAO;

    public function __construct() {
        $this->dao                = new QuestionnaireDAO();
        $this->questionDAO        = new QuestionDAO();
        $this->reponsePossibleDAO = new ReponsePossibleDAO();
    }

    
    // LISTE
    

    public function liste() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $questionnaires = $this->dao->getAll();
        $utilisateur    = $_SESSION['utilisateur'];
        include "vues/listeQuestionnaire.php";
    }

    // =====================================================================
    // CRÉATION (tout en session, rien en BDD avant le bouton Enregistrer)
    // =====================================================================

    public function formulaireCreation() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        if (!isset($_SESSION['nouveau_questionnaire'])) {
            $_SESSION['nouveau_questionnaire'] = [
                'nom'       => '',
                'theme_id'  => 0,
                'questions' => []
            ];
        }

        $utilisateur = $_SESSION['utilisateur'];
        $themes      = $this->dao->getThemes();
        $brouillon   = $_SESSION['nouveau_questionnaire'];
        include "vues/ajouterQuestionnaire.php";
    }

    public function ajouterQuestionSession() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $libelle      = $_POST['libelle']      ?? '';
        $typeReponse  = $_POST['type_reponse'] ?? 'VraiFaux';
        $bonneReponse = $typeReponse === 'VraiFaux' ? ($_POST['bonne_reponse'] ?? '') : null;

        $question = [
            'libelle'           => $libelle,
            'typeReponse'       => $typeReponse,
            'bonneReponse'      => $bonneReponse,
            'reponsesPossibles' => []
        ];

        if ($typeReponse === 'ListeValeurs') {
            $libelles  = $_POST['reponse_libelle']  ?? [];
            $correctes = $_POST['reponse_correcte'] ?? [];
            foreach ($libelles as $i => $lib) {
                if (!empty(trim($lib))) {
                    $question['reponsesPossibles'][] = [
                        'libelle'     => $lib,
                        'estCorrecte' => in_array((string)$i, $correctes)
                    ];
                }
            }
        }

        $_SESSION['nouveau_questionnaire']['questions'][] = $question;

        header('Location: index.php?action=ajouter_questionnaire');
        exit;
    }

    public function supprimerQuestionSession() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $index = (int)($_GET['index'] ?? -1);
        if (isset($_SESSION['nouveau_questionnaire']['questions'][$index])) {
            array_splice($_SESSION['nouveau_questionnaire']['questions'], $index, 1);
        }

        header('Location: index.php?action=ajouter_questionnaire');
        exit;
    }

    public function enregistrer() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $utilisateur = $_SESSION['utilisateur'];
        $nom         = $_POST['nom']     ?? '';
        $themeId     = (int)($_POST['theme_id'] ?? 0);
        $questions   = $_SESSION['nouveau_questionnaire']['questions'] ?? [];

        if (empty($nom) || $themeId <= 0 || empty($questions)) {
            header('Location: index.php?action=ajouter_questionnaire');
            exit;
        }

        // 1. Créer le questionnaire en BDD
        $q             = new Questionnaire();
        $q->nom        = $nom;
        $q->themeId    = $themeId;
        $q->createurId = $utilisateur->id;
        $newId         = $this->dao->ajouter($q);

        // 2. Créer les questions + réponses en BDD
        foreach ($questions as $i => $qData) {
            $question                  = new Question();
            $question->questionnaireId = $newId;
            $question->libelle         = $qData['libelle'];
            $question->typeReponse     = $qData['typeReponse'];
            $question->bonneReponse    = $qData['bonneReponse'];
            $question->ordre           = $i + 1;
            $questionId                = $this->questionDAO->ajouter($question);

            if ($qData['typeReponse'] === 'ListeValeurs' && $questionId > 0) {
                foreach ($qData['reponsesPossibles'] as $rData) {
                    $r              = new ReponsePossible();
                    $r->questionId  = $questionId;
                    $r->libelle     = $rData['libelle'];
                    $r->estCorrecte = $rData['estCorrecte'];
                    $this->reponsePossibleDAO->ajouter($r);
                }
            }
        }

        // 3. Vider le brouillon de session
        unset($_SESSION['nouveau_questionnaire']);

        header('Location: index.php?action=liste_questionnaires');
        exit;
    }

    public function annulerCreation() {
        unset($_SESSION['nouveau_questionnaire']);
        header('Location: index.php?action=liste_questionnaires');
        exit;
    }

    // =====================================================================
    // MODIFICATION (BDD directe)
    // =====================================================================

    public function formulaireEdition($questionnaireId = 0) {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $utilisateur     = $_SESSION['utilisateur'];
        $questionnaireId = (int)$questionnaireId;
        $questionnaire   = $this->dao->getById($questionnaireId);
        $themes          = $this->dao->getThemes();
        $questions       = $this->questionDAO->getByQuestionnaire($questionnaireId);

        foreach ($questions as $q) {
            if ($q->typeReponse === 'ListeValeurs') {
                $q->reponsesPossibles = $this->reponsePossibleDAO->getByQuestion($q->id);
            }
        }

        include "vues/modifierQuestionnaire.php";
    }

    public function sauvegarderEdition() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $utilisateur     = $_SESSION['utilisateur'];
        $questionnaireId = (int)($_POST['questionnaire_id'] ?? 0);

        $q          = new Questionnaire();
        $q->id      = $questionnaireId;
        $q->nom     = $_POST['nom']     ?? '';
        $q->themeId = (int)($_POST['theme_id'] ?? 0);

        $existant = $this->dao->getById($questionnaireId);
        if ($existant && $existant->createurId === $utilisateur->id) {
            $this->dao->modifier($q);
        }

        header("Location: index.php?action=modifier_questionnaire&id=$questionnaireId");
        exit;
    }

    public function supprimer($id) {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $utilisateur = $_SESSION['utilisateur'];
        $existant    = $this->dao->getById((int)$id);

        if ($existant && $existant->createurId === $utilisateur->id) {
            $this->dao->supprimer((int)$id);
        }
        header('Location: index.php?action=liste_questionnaires');
        exit;
    }

    // Modifier une question existante directement en BDD
    public function modifierQuestionEdition() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $questionId      = (int)($_POST['question_id']      ?? 0);
        $questionnaireId = (int)($_POST['questionnaire_id'] ?? 0);

        $q              = new Question();
        $q->id          = $questionId;
        $q->libelle     = $_POST['libelle']      ?? '';
        $q->typeReponse = $_POST['type_reponse'] ?? 'VraiFaux';
        $q->bonneReponse= $q->typeReponse === 'VraiFaux' ? ($_POST['bonne_reponse'] ?? '') : null;
        $q->ordre       = (int)($_POST['ordre']  ?? 0);
        $this->questionDAO->modifier($q);

        // Recréer les réponses si ListeValeurs
        if ($q->typeReponse === 'ListeValeurs') {
            $this->reponsePossibleDAO->supprimerParQuestion($questionId);
            $libelles  = $_POST['reponse_libelle']  ?? [];
            $correctes = $_POST['reponse_correcte'] ?? [];
            foreach ($libelles as $i => $lib) {
                if (!empty(trim($lib))) {
                    $r              = new ReponsePossible();
                    $r->questionId  = $questionId;
                    $r->libelle     = $lib;
                    $r->estCorrecte = in_array((string)$i, $correctes) || in_array('new_'.$i, $correctes);
                    $this->reponsePossibleDAO->ajouter($r);
                }
            }
        }

        header("Location: index.php?action=modifier_questionnaire&id=$questionnaireId");
        exit;
    }

    // Ajouter une question directement en BDD (depuis la vue modification)
    public function ajouterQuestionEdition() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $questionnaireId = (int)($_POST['questionnaire_id'] ?? 0);

        $q                  = new Question();
        $q->questionnaireId = $questionnaireId;
        $q->libelle         = $_POST['libelle']      ?? '';
        $q->typeReponse     = $_POST['type_reponse'] ?? 'VraiFaux';
        $q->bonneReponse    = $q->typeReponse === 'VraiFaux' ? ($_POST['bonne_reponse'] ?? '') : null;
        $q->ordre           = (int)($_POST['ordre']  ?? 0);
        $newId              = $this->questionDAO->ajouter($q);

        if ($q->typeReponse === 'ListeValeurs' && $newId > 0) {
            $libelles  = $_POST['reponse_libelle']  ?? [];
            $correctes = $_POST['reponse_correcte'] ?? [];
            foreach ($libelles as $i => $lib) {
                if (!empty(trim($lib))) {
                    $r              = new ReponsePossible();
                    $r->questionId  = $newId;
                    $r->libelle     = $lib;
                    $r->estCorrecte = in_array((string)$i, $correctes);
                    $this->reponsePossibleDAO->ajouter($r);
                }
            }
        }

        header("Location: index.php?action=modifier_questionnaire&id=$questionnaireId");
        exit;
    }

    // Supprimer une question depuis la vue modification
    public function supprimerQuestionEdition($questionId, $questionnaireId) {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $this->questionDAO->supprimer((int)$questionId);
        header("Location: index.php?action=modifier_questionnaire&id=$questionnaireId");
        exit;
    }
}