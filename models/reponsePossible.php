<?php
class ReponsePossible {
    public int    $id;
    public int    $questionId;
    public string $libelle;
    public bool   $estCorrecte;
    public int    $poids = 0;
}