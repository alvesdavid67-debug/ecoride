<?php
session_start();
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$id_user = $_SESSION['user_id'];

// Récupérer les réservations avec les infos du trajet
$sql = "SELECT reservation.*, covoiturage.lieu_depart, covoiturage.lieu_arrivee, 
        covoiturage.date_depart, covoiturage.heure_depart, covoiturage.prix_personne
        FROM reservation 
        JOIN covoiturage ON reservation.covoiturage_id = covoiturage.covoiturage_id
        WHERE reservation.utilisateur_id = ?
        ORDER BY reservation.date_reservation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_user]);
$reservations = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <h2 style="color: #248179; font-family: Poppins;" class="mb-4">Mon historique</h2>

      <?php if (empty($reservations)) : ?>
        <p>Vous n'avez pas encore de réservation.</p>

      <?php else : ?>
        <?php foreach ($reservations as $resa) : ?>
          <div class="card border-0 shadow-sm p-3 mb-3">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h5 style="color: #248179;"><?php echo $resa['lieu_depart']; ?> → <?php echo $resa['lieu_arrivee']; ?></h5>
                <p>📅 <?php echo $resa['date_depart']; ?> à <?php echo $resa['heure_depart']; ?></p>
                <p>💰 <?php echo $resa['prix_personne']; ?>€</p>
                <span class="badge" style="background-color: <?php echo $resa['statut'] === 'confirmée' ? '#B7DEAD' : '#FF6B6B'; ?>; color: #4E4F59;">
                  <?php echo $resa['statut']; ?>
                </span>
              </div>
              <div class="col-md-6 text-end">
                <?php if ($resa['statut'] === 'confirmée') : ?>
                  <a href="annuler_reservation.php?id=<?php echo $resa['reservation_id']; ?>" 
                     class="btn" 
                     style="background-color: #FB9B27; color: white;"
                     onclick="return confirm('Voulez-vous annuler cette réservation ?')">
                    Annuler
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>