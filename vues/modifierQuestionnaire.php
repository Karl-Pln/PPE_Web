<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier - <?= htmlspecialchars($questionnaire->nom) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <div class="header">
        <h1>StadiumCompany</h1>
        <a href="index.php?action=liste_questionnaires" class="btn btn-secondary">← Retour</a>
    </div>

    <h2>Modifier le questionnaire</h2>

    <!-- Nom et thème -->
    <div class="card">
        <h3>Informations générales</h3>
        <form method="POST" action="index.php?action=sauvegarder_edition">
            <input type="hidden" name="questionnaire_id" value="<?= $questionnaire->id ?>">
            <div class="form-group">
                <label>Nom du questionnaire</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($questionnaire->nom) ?>" required>
            </div>
            <div class="form-group">
                <label>Thème</label>
                <select name="theme_id" required>
                    <?php foreach ($themes as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= $questionnaire->themeId == $t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>

    <!-- Ajouter une nouvelle question -->
    <div class="card">
        <h3>Ajouter une nouvelle question</h3>
        <form method="POST" action="index.php?action=ajouter_question_edition">
            <input type="hidden" name="questionnaire_id" value="<?= $questionnaire->id ?>">
            <input type="hidden" name="ordre" value="<?= count($questions) + 1 ?>">

            <div class="form-group">
                <label>Intitulé</label>
                <input type="text" name="libelle" required>
            </div>
            <div class="form-group">
                <label>Type de réponse</label>
                <select name="type_reponse" id="typeReponseNew" onchange="toggleTypeReponseNew()">
                    <option value="VraiFaux">Vrai / Faux</option>
                    <option value="ListeValeurs">Liste de valeurs</option>
                </select>
            </div>

            <div id="blocVraiFauxNew" class="form-group">
                <label>Bonne réponse</label>
                <label class="reponse-option"><input type="radio" name="bonne_reponse" value="Vrai"> Vrai</label>
                <label class="reponse-option"><input type="radio" name="bonne_reponse" value="Faux"> Faux</label>
            </div>

            <div id="blocListeValeursNew" style="display:none;">
                <div id="listeReponsesNew"></div>
                <button type="button" class="btn btn-secondary btn-small"
                        onclick="ajouterReponseNew()">+ Ajouter une réponse</button>
                <p class="aide">Cochez la bonne réponse.</p>
            </div>

            <button type="submit" class="btn btn-primary">+ Ajouter la question</button>
        </form>
    </div>

    <!-- Questions existantes -->
    <div class="card">
        <h3>Questions (<?= count($questions) ?>)</h3>

        <?php if (empty($questions)): ?>
            <p style="color:#aaa;">Aucune question pour le moment.</p>
        <?php endif; ?>

        <?php foreach ($questions as $i => $q): ?>
        <div class="question-edition">
            <div class="question-edition-header">
                <span class="numero-question">Question <?= $i + 1 ?></span>
                <a href="index.php?action=supprimer_question_edition&id=<?= $q->id ?>&questionnaire_id=<?= $questionnaire->id ?>"
                   class="btn btn-small btn-danger"
                   onclick="return confirm('Supprimer cette question ?')">Supprimer</a>
            </div>

            <form method="POST" action="index.php?action=modifier_question_edition">
                <input type="hidden" name="question_id"       value="<?= $q->id ?>">
                <input type="hidden" name="questionnaire_id"  value="<?= $questionnaire->id ?>">
                <input type="hidden" name="ordre"             value="<?= $q->ordre ?>">

                <div class="form-group">
                    <label>Intitulé</label>
                    <input type="text" name="libelle" value="<?= htmlspecialchars($q->libelle) ?>" required>
                </div>

                <div class="form-group">
                    <label>Type de réponse</label>
                    <select name="type_reponse" id="typeReponse_<?= $q->id ?>"
                            onchange="toggleTypeReponseEdit(<?= $q->id ?>)">
                        <option value="VraiFaux"     <?= $q->typeReponse === 'VraiFaux'     ? 'selected' : '' ?>>Vrai / Faux</option>
                        <option value="ListeValeurs" <?= $q->typeReponse === 'ListeValeurs' ? 'selected' : '' ?>>Liste de valeurs</option>
                    </select>
                </div>

                <!-- VraiFaux -->
                <div id="blocVraiFaux_<?= $q->id ?>" class="form-group"
                     style="<?= $q->typeReponse !== 'VraiFaux' ? 'display:none' : '' ?>">
                    <label>Bonne réponse</label>
                    <label class="reponse-option">
                        <input type="radio" name="bonne_reponse" value="Vrai"
                            <?= $q->bonneReponse === 'Vrai' ? 'checked' : '' ?>> Vrai
                    </label>
                    <label class="reponse-option">
                        <input type="radio" name="bonne_reponse" value="Faux"
                            <?= $q->bonneReponse === 'Faux' ? 'checked' : '' ?>> Faux
                    </label>
                </div>

                <!-- ListeValeurs -->
                <div id="blocListeValeurs_<?= $q->id ?>"
                     style="<?= $q->typeReponse !== 'ListeValeurs' ? 'display:none' : '' ?>">
                    <div id="listeReponses_<?= $q->id ?>">
                        <?php foreach ($q->reponsesPossibles as $r): ?>
                        <div class="reponse-ligne">
                            <input type="text" name="reponse_libelle[]"
                                   value="<?= htmlspecialchars($r->libelle) ?>" required>
                            <label>
                                <input type="checkbox" name="reponse_correcte[]"
                                       value="<?= $r->id ?>"
                                    <?= $r->estCorrecte ? 'checked' : '' ?>> Correcte
                            </label>
                            <button type="button" onclick="this.parentElement.remove()"
                                    class="btn btn-small btn-danger">✕</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-secondary btn-small"
                            onclick="ajouterReponseEdit(<?= $q->id ?>)">+ Ajouter une réponse</button>
                    <p class="aide">Cochez la bonne réponse.</p>
                </div>

                <button type="submit" class="btn btn-primary btn-small" style="margin-top:10px;">
                    Enregistrer cette question
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    

</div>

<style>
.question-edition {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    background: #fafafa;
}
.question-edition-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
</style>

<script>
// Toggle pour les questions existantes
function toggleTypeReponseEdit(id) {
    const type = document.getElementById('typeReponse_' + id).value;
    document.getElementById('blocVraiFaux_'     + id).style.display = type === 'VraiFaux'     ? 'block' : 'none';
    document.getElementById('blocListeValeurs_' + id).style.display = type === 'ListeValeurs' ? 'block' : 'none';
}

// Toggle pour la nouvelle question
function toggleTypeReponseNew() {
    const type = document.getElementById('typeReponseNew').value;
    document.getElementById('blocVraiFauxNew').style.display     = type === 'VraiFaux'     ? 'block' : 'none';
    document.getElementById('blocListeValeursNew').style.display = type === 'ListeValeurs' ? 'block' : 'none';
}

// Ajouter une réponse dans une question existante
let compteurEdit = {};
function ajouterReponseEdit(id) {
    if (!compteurEdit[id]) compteurEdit[id] = 0;
    const container = document.getElementById('listeReponses_' + id);
    const div = document.createElement('div');
    div.className = 'reponse-ligne';
    div.innerHTML = `
        <input type="text" name="reponse_libelle[]" placeholder="Libellé" required>
        <label><input type="checkbox" name="reponse_correcte[]" value="new_${compteurEdit[id]}"> Correcte</label>
        <button type="button" onclick="this.parentElement.remove()" class="btn btn-small btn-danger">✕</button>
    `;
    container.appendChild(div);
    compteurEdit[id]++;
}

// Ajouter une réponse dans la nouvelle question
let compteurNew = 0;
function ajouterReponseNew() {
    const container = document.getElementById('listeReponsesNew');
    const div = document.createElement('div');
    div.className = 'reponse-ligne';
    div.innerHTML = `
        <input type="text" name="reponse_libelle[]" placeholder="Libellé" required>
        <label><input type="checkbox" name="reponse_correcte[]" value="${compteurNew}"> Correcte</label>
        <button type="button" onclick="this.parentElement.remove()" class="btn btn-small btn-danger">✕</button>
    `;
    container.appendChild(div);
    compteurNew++;
}
</script>
</body>
</html>