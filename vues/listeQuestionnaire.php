<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Questionnaires - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <div class="header-droite">
            <span>Bonjour <?= htmlspecialchars($utilisateur->login) ?></span>
            <a href="index.php?action=deconnexion" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <div class="toolbar">
        <h2>Liste des questionnaires</h2>
        <a href="index.php?action=ajouter_questionnaire" class="btn btn-primary">+ Nouveau</a>
    </div>

    <table class="tableau">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Thème</th>
                <th>Questions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($questionnaires as $q): ?>
            <tr>
                <td><?= htmlspecialchars($q->nom) ?></td>
                <td><?= htmlspecialchars($q->themeLibelle) ?></td>
                <td><?= $q->nbQuestions ?></td>
                <td class="actions">
                    <a href="index.php?action=questionnaire_detail&id=<?= $q->id ?>" class="btn btn-small btn-primary">Répondre</a>
                    <?php if ($q->createurId === $utilisateur->id): ?>
                        <a href="index.php?action=modifier_questionnaire&id=<?= $q->id ?>" class="btn btn-small btn-secondary">Modifier</a>
                        <a href="index.php?action=supprimer_questionnaire&id=<?= $q->id ?>"
                           class="btn btn-small btn-danger"
                           onclick="return confirm('Supprimer ce questionnaire ?')">Supprimer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($questionnaires)): ?>
            <tr>
                <td colspan="4" style="text-align:center; color:#aaa;">Aucun questionnaire pour le moment.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>