<?php
session_start();
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer les avis en attente fusion des talbles (join)
$sql = "SELECT avis.*, covoiturage.lieu_depart, covoiturage.lieu_arrivee 
        FROM avis 
        JOIN covoiturage ON avis.covoiturage_id = covoiturage.covoiturage_id
        WHERE avis.statut = 'en attente'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$avis = $stmt->fetchAll();

// Récupérer tous les trajets plus récent en 1er (Desc)
$sql2 = "SELECT * FROM covoiturage ORDER BY date_depart DESC";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$trajets = $stmt2->fetchAll();

// Traitement des avis validés ou refusés
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_avis = $_POST['id_avis'];
    $action = $_POST['action'];

    if ($action === 'valider') {
        $statut = 'validé';
    } else {
        $statut = 'refusé';
    }

    $sql3 = "UPDATE avis SET statut = ? WHERE avis_id = ?";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([$statut, $id_avis]);

    header('Location: employe.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <h2 style="color: #248179; font-family: Poppins;" class="mb-4">Espace Employé</h2>

      <!-- MODÉRATION DES AVIS -->
      <h4 style="color: #248179;" class="mb-3">Avis en attente de modération</h4>

      <?php if (empty($avis)) : ?>
        <p class="mb-5">Pas d'avis à valider</p>
      <?php else : ?>
        <?php foreach ($avis as $a) : ?>
          <div class="card border-0 shadow-sm p-3 mb-3">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h6 style="color: #248179;"><?php echo $a['lieu_depart']; ?> → <?php echo $a['lieu_arrivee']; ?></h6>
                <p>⭐ Note : <strong><?php echo $a['note']; ?>/5</strong></p>
                <p>💬 <?php echo $a['commentaire']; ?></p>
              </div>
              <div class="col-md-4 text-end">

                <!--d-inline refuser valider sur la même ligne-->
             <form method="POST" action="employe.php" class="d-inline">
                  <input type="hidden" name="id_avis" value="<?php echo $a['avis_id']; ?>">
                  <input type="hidden" name="action" value="valider">
                  <button type="submit" class="btn btn-sm me-2" style="background-color: #248179; color: white;">✅ Valider</button>
                </form>
                <form method="POST" action="employe.php" class="d-inline">
                  <input type="hidden" name="id_avis" value="<?php echo $a['avis_id']; ?>">
                  <input type="hidden" name="action" value="refuser">
                  <button type="submit" class="btn btn-sm" style="background-color: #FB9B27; color: white;">❌ Refuser</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- LISTE DES TRAJETS -->
      <h4 style="color: #248179;" class="mt-5 mb-3">Tous les trajets</h4>
      <div class="table-responsive">

        <!--tableau propre lignes alternées-->
        <table class="table table-striped bg-white">
          <thead style="background-color: #248179; color: white;">
            <!-- En tête des tableau (th)-->
            <tr>
              <th>Départ</th>
              <th>Arrivée</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Places</th>
              <th>Prix</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($trajets as $t) : ?>
            <!--corps du tableau (tr) -->
              <tr>
                <td><?php echo $t['lieu_depart']; ?></td>
                <td><?php echo $t['lieu_arrivee']; ?></td>
                <td><?php echo $t['date_depart']; ?></td>
                <td><?php echo $t['heure_depart']; ?></td>
                <td><?php echo $t['nombre_place']; ?></td>
                <td><?php echo $t['prix_personne']; ?>€</td>
                <td><?php echo $t['statut']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>