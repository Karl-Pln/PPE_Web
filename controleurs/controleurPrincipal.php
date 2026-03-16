<?php

function controleurPrincipal($action) {
    switch ($action) {

        // UTILISATEUR
        case 'connexion':
            require_once "controleurs/utilisateurControleur.php";
            $controller = new UtilisateurControleur();
            $controller->connexion();
            break;

        case 'inscription':
            require_once "controleurs/utilisateurControleur.php";
            $controller = new UtilisateurControleur();
            $controller->inscription();
            break;

        case 'deconnexion':
            require_once "controleurs/utilisateurControleur.php";
            $controller = new UtilisateurControleur();
            $controller->deconnexion();
            break;

        // QUESTIONNAIRE - LISTE
        case 'liste_questionnaires':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->liste();
            break;

        // QUESTIONNAIRE - CRÉATION (session)
        case 'ajouter_questionnaire':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->formulaireCreation();
            break;

        case 'ajouter_question_session':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->ajouterQuestionSession();
            break;

        case 'supprimer_question_session':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->supprimerQuestionSession();
            break;

        case 'enregistrer_questionnaire':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->enregistrer();
            break;

        case 'annuler_creation':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->annulerCreation();
            break;

        // QUESTIONNAIRE - MODIFICATION (BDD directe)
        case 'modifier_questionnaire':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->formulaireEdition($_GET['id'] ?? 0);
            break;

        case 'sauvegarder_edition':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->sauvegarderEdition();
            break;

        case 'supprimer_questionnaire':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->supprimer($_GET['id'] ?? 0);
            break;
        
        case 'modifier_question_edition':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->modifierQuestionEdition();
            break;

        case 'ajouter_question_edition':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->ajouterQuestionEdition();
            break;

        case 'supprimer_question_edition':
            require_once "controleurs/questionnaireControleur.php";
            $controller = new QuestionnaireControleur();
            $controller->supprimerQuestionEdition($_GET['id'] ?? 0, $_GET['questionnaire_id'] ?? 0);
            break;

        // QUESTION (dans le mode édition BDD directe)
        case 'ajouter_question':
            require_once "controleurs/questionControleur.php";
            $controller = new QuestionControleur();
            $controller->ajouter();
            break;

        case 'supprimer_question':
            require_once "controleurs/questionControleur.php";
            $controller = new QuestionControleur();
            $controller->supprimer($_GET['id'] ?? 0, $_GET['questionnaire_id'] ?? 0);
            break;

        // RÉPONDRE
        case 'questionnaire_detail':
            require_once "controleurs/repondreControleur.php";
            $controller = new RepondreControleur();
            $controller->afficher($_GET['id'] ?? 0);
            break;

        case 'soumettre_reponses':
            require_once "controleurs/repondreControleur.php";
            $controller = new RepondreControleur();
            $controller->soumettre();
            break;

        // SIGNALEMENT
        case 'signalement':
            require_once "controleurs/signalementControleur.php";
            $controller = new SignalementControleur();
            $controller->afficher();
            break;
 
        case 'envoyer_signalement':
            require_once "controleurs/signalementControleur.php";
            $controller = new SignalementControleur();
            $controller->envoyer();
            break;

        // PAR DÉFAUT
        default:
            require_once "controleurs/utilisateurControleur.php";
            $controller = new UtilisateurControleur();
            $controller->connexion();
            break;
    }
}