<?php
session_start();
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_user = $_SESSION['user_id'];

// Récupérer l'utilisateur
$sql = "SELECT * FROM utilisateur WHERE utilisateur_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_user]);
$user = $stmt->fetch();

$succes = false;
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lieu_depart = $_POST['lieu_depart'];
    $lieu_arrivee = $_POST['lieu_arrivee'];
    $date_depart = $_POST['date_depart'];
    $heure_depart = $_POST['heure_depart'];
    $date_arrivee = $_POST['date_arrivee'];
    $heure_arrivee = $_POST['heure_arrivee'];
    $nombre_place = $_POST['nombre_place'];
    $prix_personne = $_POST['prix_personne'];
    $voyage_eco = isset($_POST['voyage_eco']) ? 1 : 0;

    if ($user['credits'] < 2) {
        $erreur = "Crédits insuffisants pour proposer un trajet (2 crédits nécessaires).";
    } else {
        // Insérer le trajet
        $sql2 = "INSERT INTO covoiturage (lieu_depart, lieu_arrivee, date_depart, heure_depart, date_arrivee, heure_arrivee, nombre_place, prix_personne, voyage_eco, statut) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'actif')";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$lieu_depart, $lieu_arrivee, $date_depart, $heure_depart, $date_arrivee, $heure_arrivee, $nombre_place, $prix_personne, $voyage_eco]);

        // Prélever 2 crédits
        $sql3 = "UPDATE utilisateur SET credits = credits - 2 WHERE utilisateur_id = ?";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([$id_user]);

        $succes = true;
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card border-0 shadow-sm p-4">
            <h2 style="color: #248179; font-family: Poppins;" class="text-center mb-4">Proposer un trajet</h2>

            <?php if ($succes) : ?>
              <div class="text-center">
                <h4 style="color: #248179;">✅ Trajet publié !</h4>
                <p>Votre trajet a été ajouté. 2 crédits ont été prélevés.</p>
                <p>Crédits restants : <strong style="color: #FB9B27;"><?php echo $user['credits'] - 2; ?></strong></p>
                <a href="index.php" class="btn mt-3" style="background-color: #248179; color: white;">Retour à l'accueil</a>
              </div>

            <?php else : ?>
              <?php if ($erreur) : ?>
                <div class="alert alert-danger"><?php echo $erreur; ?></div>
              <?php endif; ?>

              <form method="POST" action="proposer_trajet.php">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Ville de départ</label>
                    <input type="text" name="lieu_depart" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Ville d'arrivée</label>
                    <input type="text" name="lieu_arrivee" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Date de départ</label>
                    <input type="date" name="date_depart" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Heure de départ</label>
                    <input type="time" name="heure_depart" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Date d'arrivée</label>
                    <input type="date" name="date_arrivee" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Heure d'arrivée</label>
                    <input type="time" name="heure_arrivee" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Nombre de places</label>
                    <input type="number" name="nombre_place" class="form-control" min="1" max="8" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Prix par personne (en crédits)</label>
                    <input type="number" name="prix_personne" class="form-control" min="1" required>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input type="checkbox" name="voyage_eco" class="form-check-input" id="voyage_eco">
                    <label class="form-check-label" for="voyage_eco">🌿 Voyage Éco (véhicule électrique)</label>
                  </div>
                </div>
                <button type="submit" class="btn w-100" style="background-color: #FB9B27; color: white;">
                  Publier le trajet
                </button>
              </form>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

