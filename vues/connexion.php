<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - StadiumCompany</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container-small">
    <h1>StadiumCompany</h1>
    <div class="card">
        <h2>Connexion</h2>
        <?php if (!empty($erreur)): ?>
            <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?action=connexion">
            <div class="form-group">
                <label>Login</label>
                <input type="text" name="login" required autofocus>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p class="lien-bas">Pas encore de compte ? <a href="index.php?action=inscription">S'inscrire</a></p>
    </div>
</div>
</body>
</html>