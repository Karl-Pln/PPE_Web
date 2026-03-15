<?php
require_once __DIR__ . '/../dao/scoreDAO.php';
require_once __DIR__ . '/../models/score.php';

class ScoreController {
    private ScoreDAO $dao;

    public function __construct() {
        $this->dao = new ScoreDAO();
    }

    public function enregistrer(int $utilisateurId, int $questionnaireId, int $scoreObtenu, int $scoreMax): bool {
        if ($utilisateurId <= 0 || $questionnaireId <= 0) return false;
        $s                  = new Score();
        $s->utilisateurId   = $utilisateurId;
        $s->questionnaireId = $questionnaireId;
        $s->scoreObtenu     = $scoreObtenu;
        $s->scoreMax        = $scoreMax;
        $s->datePassage     = date('Y-m-d H:i:s');
        return $this->dao->enregistrer($s);
    }

    public function getByUtilisateur(int $utilisateurId): array {
        if ($utilisateurId <= 0) return [];
        return $this->dao->getByUtilisateur($utilisateurId);
    }
}