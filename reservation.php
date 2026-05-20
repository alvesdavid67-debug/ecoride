<?php
var_dump("page chargée");
session_start();
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_trajet = $_GET['id'];
$id_user = $_SESSION['user_id'];

// Récupérer le trajet
$sql = "SELECT * FROM covoiturage WHERE covoiturage_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch();

// Récupérer l'utilisateur
$sql2 = "SELECT * FROM utilisateur WHERE utilisateur_id = ?";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([$id_user]);
$user = $stmt2->fetch();

$erreur = '';
$succes = false;

// Traitement confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user['credits'] < $trajet['prix_personne']) {
        $erreur = "Crédits insuffisants.";
    } elseif ($trajet['nombre_place'] < 1) {
        $erreur = "Plus de places disponibles.";
    } else {
        // Enregistrer la réservation
        $sql3 = "INSERT INTO reservation (utilisateur_id, covoiturage_id, statut, date_reservation) VALUES (?, ?, 'confirmée', CURDATE())";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([$id_user, $id_trajet]);

        // Débiter les crédits
        $sql4 = "UPDATE utilisateur SET credits = credits - ? WHERE utilisateur_id = ?";
        $stmt4 = $pdo->prepare($sql4);
        $stmt4->execute([$trajet['prix_personne'], $id_user]);

        // Mise à jour des places
        $sql5 = "UPDATE covoiturage SET nombre_place = nombre_place - 1 WHERE covoiturage_id = ?";
        $stmt5 = $pdo->prepare($sql5);
        $stmt5->execute([$id_trajet]);

        $succes = true;
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm p-4">

            <?php if ($succes) : ?>
              <div class="text-center">
                <h2 style="color: #248179;">✅ Réservation confirmée !</h2>
                <p>Votre trajet <strong><?php echo $trajet['lieu_depart']; ?> → <?php echo $trajet['lieu_arrivee']; ?></strong> est réservé.</p>
                <p>Crédits restants : <strong style="color: #FB9B27;"><?php echo $user['credits'] - $trajet['prix_personne']; ?></strong></p>
                <a href="index.php" class="btn mt-3" style="background-color: #248179; color: white;">Retour à l'accueil</a>
              </div>

            <?php elseif ($erreur) : ?>
              <div class="alert alert-danger"><?php echo $erreur; ?></div>
              <a href="detail.php?id=<?php echo $trajet['covoiturage_id']; ?>" class="btn w-100" style="background-color: #248179; color: white;">Retour au trajet</a>

            <?php else : ?>
              <h4 style="color: #248179;" class="text-center mb-4">Confirmer la réservation</h4>
              <p>Trajet : <strong><?php echo $trajet['lieu_depart']; ?> → <?php echo $trajet['lieu_arrivee']; ?></strong></p>
              <p>Date : <strong><?php echo $trajet['date_depart']; ?></strong></p>
              <p>Prix : <strong style="color: #FB9B27;"><?php echo $trajet['prix_personne']; ?>€</strong></p>
              <p>Vos crédits : <strong><?php echo $user['credits']; ?></strong></p>

              <form method="POST" action="reservation.php?id=<?php echo $trajet['covoiturage_id']; ?>">
                <button type="submit" class="btn w-100 mt-3" style="background-color: #FB9B27; color: white;">
                  ✅ Confirmer la réservation
                </button>
              </form>
              <a href="detail.php?id=<?php echo $trajet['covoiturage_id']; ?>" class="btn w-100 mt-2" style="background-color: #248179; color: white;">
                Annuler
              </a>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>