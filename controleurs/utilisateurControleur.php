<?php
require_once "dao/utilisateurDAO.php";
require_once "models/utilisateur.php";

class UtilisateurControleur {

    private UtilisateurDAO $dao;

    public function __construct() {
        $this->dao = new UtilisateurDAO();
    }

    public function connexion() {
        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login    = $_POST['login']    ?? '';
            $password = $_POST['password'] ?? '';
            $hash     = hash('sha256', $password);

            $utilisateur = $this->dao->connexion($login, $hash);
            if ($utilisateur) {
                $_SESSION['utilisateur'] = $utilisateur;
                header('Location: index.php?action=liste_questionnaires');
                exit;
            }
            $erreur = "Login ou mot de passe incorrect.";
        }
        include "vues/connexion.php";
    }

    public function inscription() {
        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login    = $_POST['login']    ?? '';
            $password = $_POST['password'] ?? '';
            $nom      = $_POST['nom']      ?? '';
            $prenom   = $_POST['prenom']   ?? '';

            if (empty($login) || empty($password) || empty($nom) || empty($prenom)) {
                $erreur = "Tous les champs sont obligatoires.";
            } elseif ($this->dao->loginExiste($login)) {
                $erreur = "Ce login est déjà utilisé.";
            } else {
                $u           = new Utilisateur();
                $u->login    = $login;
                $u->password = hash('sha256', $password);
                $u->nom      = $nom;
                $u->prenom   = $prenom;
                $this->dao->inscrire($u);
                header('Location: index.php?action=connexion');
                exit;
            }
        }
        include "vues/inscription.php";
    }

    public function deconnexion() {
        session_destroy();
        header('Location: index.php?action=connexion');
        exit;
    }
}