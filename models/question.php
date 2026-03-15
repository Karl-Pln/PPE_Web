<?php
class Question {
    public int    $id;
    public int    $questionnaireId;
    public string $libelle;
    public string $typeReponse;    // 'VraiFaux' ou 'ListeValeurs'
    public ?string $bonneReponse;  // 'Vrai' ou 'Faux' pour VraiFaux
    public int    $ordre;
    public array  $reponsesPossibles = [];
}