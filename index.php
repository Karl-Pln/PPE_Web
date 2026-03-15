<?php
require_once "config/db.php";
require_once "models/utilisateur.php";
session_start();
require_once "controleurs/controleurPrincipal.php";

$action = $_GET['action'] ?? 'connexion';
controleurPrincipal($action);