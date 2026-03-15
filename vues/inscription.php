<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container-small">
    <h1>StadiumCompany</h1>
    <div class="card">
        <h2>Inscription</h2>
        <?php if (!empty($erreur)): ?>
            <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?action=inscription">
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Login</label>
                <input type="text" name="login" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="lien-bas">Déjà un compte ? <a href="index.php?action=connexion">Se connecter</a></p>
    </div>
</div>
</body>
</html>