<?php
class Score {
    public int    $id;
    public int    $utilisateurId;
    public int    $questionnaireId;
    public string $questionnaireNom;
    public int    $scoreObtenu;
    public int    $scoreMax;
    public string $datePassage;
}