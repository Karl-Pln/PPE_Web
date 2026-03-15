<?php
class Questionnaire {
    public int    $id;
    public string $nom;
    public int    $themeId;
    public string $themeLibelle;
    public int    $createurId;
    public int    $nbQuestions = 0;
    public array  $questions   = [];
}