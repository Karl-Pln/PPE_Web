<?php
require_once "config/db.php";
require_once "models/utilisateur.php";

class UtilisateurDAO {

    public function connexion(string $login, string $password): ?Utilisateur {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login AND password = :password");
            $stmt->execute([':login' => $login, ':password' => $password]);
            $row  = $stmt->fetch();
            if ($row) {
                $u           = new Utilisateur();
                $u->id       = $row['id'];
                $u->login    = $row['login'];
                $u->password = $row['password'];
                $u->nom      = $row['nom'];
                $u->prenom   = $row['prenom'];
                return $u;
            }
        } catch (Exception $e) {
            error_log("DAO connexion : " . $e->getMessage());
        }
        return null;
    }

    public function loginExiste(string $login): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE login = :login");
            $stmt->execute([':login' => $login]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("DAO loginExiste : " . $e->getMessage());
        }
        return false;
    }

    public function inscrire(Utilisateur $u): bool {
        try {
            $pdo  = DBConnexion::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, password, nom, prenom) VALUES (:login, :password, :nom, :prenom)");
            $stmt->execute([
                ':login'    => $u->login,
                ':password' => $u->password,
                ':nom'      => $u->nom,
                ':prenom'   => $u->prenom
            ]);
            return true;
        } catch (Exception $e) {
            error_log("DAO inscrire : " . $e->getMessage());
        }
        return false;
    }
}