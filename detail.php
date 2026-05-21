<?php
session_start();
include 'includes/db.php';

$id = $_GET['id'];

$sql = "SELECT covoiturage.*, voiture.modele, voiture.energie, voiture.couleur, voiture.tabac, voiture.animal 
        FROM covoiturage 
        LEFT JOIN voiture ON covoiturage.voiture_id = voiture.voiture_id
        WHERE covoiturage.covoiturage_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$trajet = $stmt->fetch();

if (!$trajet) {
    header('Location: covoiturage.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <div class="row">

        <!-- BLOC GAUCHE : Infos trajet -->
        <div class="col-md-6 mb-4">
          <div class="card border-0 shadow-sm p-4">
            <h4 style="color: #248179;">
              <?php echo $trajet['lieu_depart']; ?> → <?php echo $trajet['lieu_arrivee']; ?>
            </h4>
            <?php if ($trajet['voyage_eco']) : ?>
              <span class="badge mb-3" style="background-color: #B7DEAD; color: #248179;">🌿 Voyage Éco</span>
            <?php endif; ?>
            <p>📅 Date : <strong><?php echo $trajet['date_depart']; ?></strong></p>
            <p>🕐 Départ : <strong><?php echo $trajet['heure_depart']; ?></strong></p>
            <p>🕓 Arrivée : <strong><?php echo $trajet['heure_arrivee']; ?></strong></p>
            <p>💺 Places restantes : <strong><?php echo $trajet['nombre_place']; ?></strong></p>
            <p>💰 Prix : <strong style="color: #FB9B27;"><?php echo $trajet['prix_personne']; ?>€ / personne</strong></p>
            <?php if ($trajet['modele']) : ?>
    <hr>
    <h6 style="color: #248179;">🚗 Véhicule</h6>
    <p><?php echo $trajet['modele']; ?> — <?php echo $trajet['couleur']; ?> — <?php echo $trajet['energie']; ?></p>
    
    <h6 style="color: #248179;">Préférences du conducteur</h6>
    <span class="badge me-2" style="background-color: #FED8A9; color: #4E4F59;">
        🚬 Fumeur : <?php echo $trajet['tabac'] ? 'Oui' : 'Non'; ?>
    </span>
    <span class="badge" style="background-color: #FED8A9; color: #4E4F59;">
        🐾 Animaux : <?php echo $trajet['animal'] ? 'Oui' : 'Non'; ?>
    </span>
    
<?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])) : ?>
              <a href="reservation.php?id=<?php echo $trajet['covoiturage_id']; ?>" 
                 class="btn w-100 mt-3" 
                 style="background-color: #FB9B27; color: white;">
                ✅ Réserver ce trajet
              </a>
            <?php else : ?>
              <a href="connexion.php" class="btn w-100 mt-3" style="background-color: #248179; color: white;">
                Connectez-vous pour réserver
              </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- BLOC DROITE : Avis -->
        <div class="col-md-6 mb-4">
          <div class="card border-0 shadow-sm p-4">
            <h4 style="color: #248179;">Avis des passagers</h4>
            <p style="color: #4E4F59;">Aucun avis pour le moment.</p>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>