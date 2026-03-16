<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Signaler un problème - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <a href="index.php?action=questionnaire_detail&id=<?= htmlspecialchars($idQuestionnaire) ?>"
           class="btn btn-secondary">← Retour au questionnaire</a>
    </div>

    <div class="card">
        <h2>Signaler un problème</h2>

        <div class="signalement-contexte">
            <p><strong>Questionnaire :</strong> <?= htmlspecialchars($nomQuestionnaire) ?></p>
            <p><strong>Question :</strong> <?= htmlspecialchars($libelleQuestion) ?></p>
        </div>

        <?php if (!empty($erreur)): ?>
            <p class="message-erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <?php if (!empty($succes)): ?>
            <p class="message-succes"><?= htmlspecialchars($succes) ?></p>
        <?php endif; ?>

        <?php if (empty($succes)): ?>
        <form method="POST" action="index.php?action=envoyer_signalement">
            <input type="hidden" name="id_question"      value="<?= htmlspecialchars($idQuestion) ?>">
            <input type="hidden" name="id_questionnaire" value="<?= htmlspecialchars($idQuestionnaire) ?>">

            <div class="form-group">
                <label for="message">Décrivez le problème :</label>
                <textarea
                    id="message"
                    name="message"
                    rows="5"
                    placeholder="décrivez le problème en quelques mots"
                    required><?= htmlspecialchars($messageAncien ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer le signalement</button>
        </form>
        <?php else: ?>
            <a href="index.php?action=questionnaire_detail&id=<?= htmlspecialchars($idQuestionnaire) ?>"
               class="btn btn-primary">Retour au questionnaire</a>
        <?php endif; ?>
    </div>

</div>
</body>
</html>