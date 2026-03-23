
-- 1. Table des utilisateurs

CREATE TABLE utilisateurs (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    login    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom      VARCHAR(100) NOT NULL,
    prenom   VARCHAR(100) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0
);


-- 2. Table des thèmes

CREATE TABLE theme (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL
);


-- 3. Table des questionnaires

CREATE TABLE questionnaire (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nom      VARCHAR(200) NOT NULL,
    theme_id INT NOT NULL,
  --  createur_id INT NOT NULL,
    FOREIGN KEY (theme_id) REFERENCES theme(id)
  --  FOREIGN KEY (createur_id) REFERENCES utilisateurs(id)
);


-- 4. Table des questions

CREATE TABLE question (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    questionnaire_id  INT NOT NULL,
    libelle           TEXT NOT NULL,
    type_reponse      ENUM('VraiFaux', 'ListeValeurs') NOT NULL,
    bonne_reponse    VARCHAR(10) DEFAULT NULL,
    ordre             INT DEFAULT 0,
    FOREIGN KEY (questionnaire_id) REFERENCES questionnaire(id) ON DELETE CASCADE
);


-- 5. Table des réponses possibles

CREATE TABLE reponse_possible (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    libelle     VARCHAR(255) NOT NULL,
    est_correcte TINYINT(1) DEFAULT 0,
    poids       INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES question(id) ON DELETE CASCADE
);

-- 6. Table des signalements

CREATE TABLE signalement (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_question INT NOT NULL,
    id_questionnaire INT NOT NULL,
    message  TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('corrigé', 'à corriger', 'en correction') DEFAULT 'à corriger',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_question) REFERENCES question(id) ON DELETE CASCADE
    FOREIGN KEY (id_questionnaire) REFERENCES questionnaire(id) ON DELETE CASCADE
);