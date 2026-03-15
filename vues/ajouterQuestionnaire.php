<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau questionnaire - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <a href="index.php?action=annuler_creation" class="btn btn-secondary">← Annuler</a>
    </div>

    <h2>Nouveau questionnaire</h2>

    <!-- Questions déjà ajoutées -->
    <?php if (!empty($brouillon['questions'])): ?>
    <div class="card">
        <h3>Questions ajoutées (<?= count($brouillon['questions']) ?>)</h3>
        <table class="tableau">
            <thead>
                <tr><th>N°</th><th>Question</th><th>Type</th><th>Bonne réponse</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($brouillon['questions'] as $i => $q): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($q['libelle']) ?></td>
                    <td><?= $q['typeReponse'] ?></td>
                    <td>
                        <?php if ($q['typeReponse'] === 'VraiFaux'): ?>
                            <?= htmlspecialchars($q['bonneReponse'] ?? '') ?>
                        <?php else: ?>
                            <?php foreach ($q['reponsesPossibles'] as $r): ?>
                                <?php if ($r['estCorrecte']): ?>
                                    <span class="badge-succes"><?= htmlspecialchars($r['libelle']) ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?action=supprimer_question_session&index=<?= $i ?>"
                           class="btn btn-small btn-danger"
                           onclick="return confirm('Supprimer cette question ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    

    <!-- Enregistrement final -->
    <div class="card">
        <h3>Informations du questionnaire</h3>
        <form method="POST" action="index.php?action=enregistrer_questionnaire">
            <div class="form-group">
                <label>Nom du questionnaire</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($brouillon['nom']) ?>" required>
            </div>
            <div class="form-group">
                <label>Thème</label>
                <select name="theme_id" required>
                    <?php foreach ($themes as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= $brouillon['theme_id'] == $t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (empty($brouillon['questions'])): ?>
                <p class="erreur">Ajoutez au moins une question avant d'enregistrer.</p>
            <?php else: ?>
                <button type="submit" class="btn btn-primary">Enregistrer le questionnaire</button>
            <?php endif; ?>
        </form>
    </div>
    <!-- Ajouter une question -->
    <div class="card">
        <h3>Ajouter une question</h3>
        <form method="POST" action="index.php?action=ajouter_question_session">
            <div class="form-group">
                <label>Intitulé</label>
                <input type="text" name="libelle" required>
            </div>
            <div class="form-group">
                <label>Type de réponse</label>
                <select name="type_reponse" id="typeReponse" onchange="toggleTypeReponse()">
                    <option value="VraiFaux">Vrai / Faux</option>
                    <option value="ListeValeurs">Liste de valeurs</option>
                </select>
            </div>

            <!-- VraiFaux -->
            <div id="blocVraiFaux" class="form-group">
                <label>Bonne réponse</label>
                <label class="reponse-option"><input type="radio" name="bonne_reponse" value="Vrai"> Vrai</label>
                <label class="reponse-option"><input type="radio" name="bonne_reponse" value="Faux"> Faux</label>
            </div>

            <!-- ListeValeurs -->
            <div id="blocListeValeurs" style="display:none;">
                <div id="listeReponses"></div>
                <button type="button" class="btn btn-secondary btn-small" onclick="ajouterReponse()">+ Ajouter une réponse</button>
                <p class="aide">Cochez la bonne réponse.</p>
            </div>

            <button type="submit" class="btn btn-primary">+ Ajouter la question</button>
        </form>
    </div>

</div>

<script>
let nbReponses = 0;

function toggleTypeReponse() {
    const type = document.getElementById('typeReponse').value;
    document.getElementById('blocVraiFaux').style.display     = type === 'VraiFaux'     ? 'block' : 'none';
    document.getElementById('blocListeValeurs').style.display = type === 'ListeValeurs' ? 'block' : 'none';
}

function ajouterReponse() {
    const container = document.getElementById('listeReponses');
    const div = document.createElement('div');
    div.className = 'reponse-ligne';
    div.innerHTML = `
        <input type="text" name="reponse_libelle[]" placeholder="Libellé de la réponse" required>
        <label><input type="checkbox" name="reponse_correcte[]" value="${nbReponses}"> Correcte</label>
        <button type="button" onclick="this.parentElement.remove()" class="btn btn-small btn-danger">✕</button>
    `;
    container.appendChild(div);
    nbReponses++;
}
</script>
</body>
</html>