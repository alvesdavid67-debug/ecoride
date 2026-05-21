<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'includes/db.php';

$trajets = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['depart'])) {
    $depart = $_POST['depart'] ?? $_GET['depart'];
    $arrivee = $_POST['arrivee'] ?? $_GET['arrivee'];
    $date = $_POST['date'] ?? $_GET['date'];

    $sql = "SELECT * FROM covoiturage 
            WHERE lieu_depart LIKE ? 
            AND lieu_arrivee LIKE ? 
            AND date_depart = ? 
            AND nombre_place >= 1
            AND statut = 'actif'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$depart%", "%$arrivee%", $date]);
    $trajets = $stmt->fetchAll();
}
?>


<?php 
session_start();
include 'includes/db.php';
include 'includes/header.php'; 
?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">

      <!-- FORMULAIRE DE RECHERCHE -->
      <div class="card border-0 shadow-sm p-4 mb-4">
        <form method="POST" action="covoiturage.php">
          <div class="row">
            <div class="col-md-4">
              <input type="text" name="depart" class="form-control" placeholder="Ville de départ" required>
            </div>
            <div class="col-md-4">
              <input type="text" name="arrivee" class="form-control" placeholder="Ville d'arrivée" required>
            </div>
            <div class="col-md-3">
              <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-1">
              <button type="submit" class="btn w-100" style="background-color: #FB9B27; color: white;">Go</button>
            </div>
          </div>
        </form>
      </div>

      <!-- RÉSULTATS -->
      <div class="row" id="resultats">
    <?php if (empty($trajets) && $_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <p class="text-center" style="color: #4E4F59;">Aucun trajet trouvé pour cette recherche.</p>
    <?php elseif (!empty($trajets)) : ?>
        <?php foreach ($trajets as $trajet) : ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body">
                        <h5 style="color: #248179;"><?php echo $trajet['lieu_depart']; ?> → <?php echo $trajet['lieu_arrivee']; ?></h5>
                        <p>📅 <?php echo $trajet['date_depart']; ?></p>
                        <p>🕐 <?php echo $trajet['heure_depart']; ?> → <?php echo $trajet['heure_arrivee']; ?></p>
                        <p>💺 <?php echo $trajet['nombre_place']; ?> places restantes</p>
                        <p>💰 <?php echo $trajet['prix_personne']; ?>€ / personne</p>
                        <?php if ($trajet['voyage_eco']) : ?>
                            <span class="badge" style="background-color: #B7DEAD; color: #248179;">🌿 Voyage Éco</span>
                        <?php endif; ?>
                        <a href="detail.php?id=<?php echo $trajet['covoiturage_id']; ?>" class="btn w-100 mt-3" style="background-color: #FB9B27; color: white;">Détails</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="text-center" style="color: #4E4F59;">Entrez un départ, une arrivée et une date pour rechercher.</p>
    <?php endif; ?>
</div>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>