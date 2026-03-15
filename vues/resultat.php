<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <a href="index.php?action=liste_questionnaires" class="btn btn-secondary">← Retour à la liste</a>
    </div>

    <div class="card resultat">
        <h2>Résultat — <?= htmlspecialchars($questionnaire->nom) ?></h2>
        <p class="score-principal"><?= $score ?> / <?= $total ?></p>
        <p class="message-score">
            <?php
            $pct = $total > 0 ? ($score / $total) * 100 : 0;
            if ($pct == 100)    echo "🎉 Parfait !";
            elseif ($pct >= 75) echo "👍 Très bien !";
            elseif ($pct >= 50) echo "😊 Pas mal !";
            else                echo "📚 À revoir...";
            ?>
        </p>

        <table class="tableau">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Votre réponse</th>
                    <th>Bonne réponse</th>
                    <th>Résultat</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($details as $d): ?>
                <tr class="<?= $d['estCorrecte'] ? 'ligne-succes' : 'ligne-erreur' ?>">
                    <td><?= htmlspecialchars($d['libelle']) ?></td>
                    <td><?= htmlspecialchars($d['reponse']) ?></td>
                    <td><?= htmlspecialchars($d['bonne']) ?></td>
                    <td><?= $d['estCorrecte'] ? '✔' : '✘' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php?action=liste_questionnaires" class="btn btn-primary">Retour à la liste</a>
    </div>

</div>
</body>
</html>