<?php
require_once "dao/questionDAO.php";
require_once "dao/reponsePossibleDAO.php";
require_once "models/question.php";
require_once "models/reponsePossible.php";

class QuestionControleur {

    private QuestionDAO        $dao;
    private ReponsePossibleDAO $reponsePossibleDAO;

    public function __construct() {
        $this->dao                = new QuestionDAO();
        $this->reponsePossibleDAO = new ReponsePossibleDAO();
    }

    public function ajouter() {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }

        $questionnaireId = (int) ($_POST['questionnaire_id'] ?? 0);

        $q                  = new Question();
        $q->questionnaireId = $questionnaireId;
        $q->libelle         = $_POST['libelle']      ?? '';
        $q->typeReponse     = $_POST['type_reponse'] ?? 'VraiFaux';
        $q->bonneReponse    = $q->typeReponse === 'VraiFaux' ? ($_POST['bonne_reponse'] ?? '') : null;
        $q->ordre           = (int) ($_POST['ordre'] ?? 0);

        $newId = $this->dao->ajouter($q);

        if ($q->typeReponse === 'ListeValeurs' && $newId > 0) {
            $libelles  = $_POST['reponse_libelle']  ?? [];
            $correctes = $_POST['reponse_correcte'] ?? [];
            foreach ($libelles as $i => $lib) {
                if (!empty(trim($lib))) {
                    $r              = new ReponsePossible();
                    $r->questionId  = $newId;
                    $r->libelle     = $lib;
                    $r->estCorrecte = in_array((string) $i, $correctes);
                    $this->reponsePossibleDAO->ajouter($r);
                }
            }
        }

        header("Location: index.php?action=ajouter_questionnaire&id=$questionnaireId");
        exit;
    }

    public function supprimer($id, $questionnaireId) {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: index.php?action=connexion');
            exit;
        }
        $this->dao->supprimer((int) $id);
        header("Location: index.php?action=ajouter_questionnaire&id=$questionnaireId");
        exit;
    }
}