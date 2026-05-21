<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta chartset ="UTF-8">
    <meta name="viewport" content ="width=device-width,initial-scale=1.0">
    <title>EcoRide</title>
    <!--BOOTSTRAP-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--CSS-->
    <link rel="stylesheet" href= "/ecoride/assets/css/style.css">
    <!--GOOGLE FONTS-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Inter:wght@400;500&display=swap"rel="stylesheet">
</head>
<body>
    
<nav class="navbar navbar-expand-lg" style="background-color: #248179;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="/index.php">EcoRide</a>
        <button class="navbar-toggler" type="button"  data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item px -3">
                    <a class="nav-link text-white" href="/index.php">Accueil</a>
                </li>    
                <li class="nav-item px -3">
                    <a class="nav-link text-white" href="/covoiturage.php">Covoiturage</a>
                </li>
                <li class="nav-item px -3">
                    <a class="nav-link text-white" href="/connexion.php">Connexion</a>
                </li>
                <li class="nav-item px -3">
                    <a class="nav-link text-white" href="/contact.php">Contact</a>
                </li>    
                <?php if (isset($_SESSION['user_id'])) : ?>
        <li class="nav-item px-3">
            <a class="nav-link text-white" href="/historique.php">Mon historique</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link text-white" href="/proposer_trajet.php">Proposer un trajet</a>
        </li>
        <li class="nav-item px-3">
            <span class="nav-link text-white">👤 <?php echo $_SESSION['pseudo']; ?></span>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="/deconnexion.php" style="color: #FB9B27;">Déconnexion</a>
        </li>

    <?php else : ?>
        <li class="nav-item px-3">
            <a class="nav-link text-white" href="/connexion.php">Connexion</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link text-white" href="/inscription.php">Inscription</a>
        </li>
    <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>