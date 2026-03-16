<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($questionnaire->nom) ?> - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <a href="index.php?action=liste_questionnaires" class="btn btn-secondary">← Retour</a>
    </div>

    <h2><?= htmlspecialchars($questionnaire->nom) ?></h2>
    <p class="sous-titre"><?= htmlspecialchars($questionnaire->themeLibelle) ?> — <?= count($questions) ?> question(s)</p>

    <form method="POST" action="index.php?action=soumettre_reponses" class="card">
        <input type="hidden" name="questionnaire_id" value="<?= $questionnaire->id ?>">

        <?php foreach ($questions as $i => $q): ?>
        <div class="question-bloc">

            <div class="question-header">
                <p class="numero-question">Question <?= $i + 1 ?> / <?= count($questions) ?></p>
                <a href="index.php?action=signalement&id_question=<?= $q->id ?>&id_questionnaire=<?= $questionnaire->id ?>"
                   class="btn btn-signalement"
                   title="Signaler un problème sur cette question">
                    Signaler
                </a>
            </div>

            <p class="libelle-question"><?= htmlspecialchars($q->libelle) ?></p>

            <?php if ($q->typeReponse === 'VraiFaux'): ?>
                <label class="reponse-option">
                    <input type="radio" name="reponse_<?= $q->id ?>" value="Vrai" required> Vrai
                </label>
                <label class="reponse-option">
                    <input type="radio" name="reponse_<?= $q->id ?>" value="Faux"> Faux
                </label>
            <?php else: ?>
                <?php foreach ($q->reponsesPossibles as $r): ?>
                    <label class="reponse-option">
                        <input type="radio" name="reponse_<?= $q->id ?>" value="<?= $r->id ?>" required>
                        <?= htmlspecialchars($r->libelle) ?>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Terminer et voir mon score</button>
    </form>

</div>
</body>
</html>