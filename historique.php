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

// Récupérer les trajets proposés par l'utilisateur
$sql_trajets = "SELECT * FROM covoiturage WHERE statut IN ('actif', 'en cours') ORDER BY date_depart DESC";
$stmt_trajets = $pdo->prepare($sql_trajets);
$stmt_trajets->execute();
$mes_trajets = $stmt_trajets->fetchAll();

// Traitement des boutons démarrer/arriver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['demarrer'])) {
        $sql_dem = "UPDATE covoiturage SET statut = 'en cours' WHERE covoiturage_id = ?";
        $stmt_dem = $pdo->prepare($sql_dem);
        $stmt_dem->execute([$_POST['trajet_id']]);
    }
    if (isset($_POST['arriver'])) {
        $sql_arr = "UPDATE covoiturage SET statut = 'terminé' WHERE covoiturage_id = ?";
        $stmt_arr = $pdo->prepare($sql_arr);
        $stmt_arr->execute([$_POST['trajet_id']]);
    }
    header('Location: historique.php');
    exit;
}
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

      <!-- TRAJETS PROPOSÉS -->
<h4 style="color: #248179;" class="mt-5 mb-3">Mes trajets proposés</h4>

<?php if (empty($mes_trajets)) : ?>
    <p>Vous n'avez pas encore proposé de trajet.</p>
<?php else : ?>
    <?php foreach ($mes_trajets as $t) : ?>
        <div class="card border-0 shadow-sm p-3 mb-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 style="color: #248179;"><?php echo $t['lieu_depart']; ?> → <?php echo $t['lieu_arrivee']; ?></h5>
                    <p>📅 <?php echo $t['date_depart']; ?> à <?php echo $t['heure_depart']; ?></p>
                    <p>💺 <?php echo $t['nombre_place']; ?> places — 💰 <?php echo $t['prix_personne']; ?>€</p>
                    <span class="badge" style="background-color: 
                        <?php echo $t['statut'] === 'actif' ? '#B7DEAD' : ($t['statut'] === 'en cours' ? '#FB9B27' : '#4E4F59'); ?>; 
                        color: white;">
                        <?php echo $t['statut']; ?>
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <?php if ($t['statut'] === 'actif') : ?>
                        <form method="POST" action="historique.php" class="d-inline">
                            <input type="hidden" name="trajet_id" value="<?php echo $t['covoiturage_id']; ?>">
                            <button type="submit" name="demarrer" class="btn btn-sm" 
                                    style="background-color: #248179; color: white;">
                                ▶ Démarrer
                            </button>
                        </form>
                    <?php elseif ($t['statut'] === 'en cours') : ?>
                        <form method="POST" action="historique.php" class="d-inline">
                            <input type="hidden" name="trajet_id" value="<?php echo $t['covoiturage_id']; ?>">
                            <button type="submit" name="arriver" class="btn btn-sm"
                                    style="background-color: #FB9B27; color: white;"
                                    onclick="return confirm('Confirmer l\'arrivée à destination ?')">
                                🏁 Arrivée à destination
                            </button>
                        </form>
                    <?php else : ?>
                        <span style="color: #4E4F59;">✅ Trajet terminé</span>
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