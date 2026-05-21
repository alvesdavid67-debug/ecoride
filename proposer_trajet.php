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
    $immatriculation = $_POST['immatriculation'];
    $date_premiere_immatriculation = $_POST['date_premiere_immatriculation'];
    $modele = $_POST['modele'];
    $marque = $_POST['marque'];
    $couleur = $_POST['couleur'];
    $energie = $_POST['energie'];
    $tabac = isset($_POST['tabac']) ? 1 : 0;
    $animal = isset($_POST['animal']) ? 1 : 0;

    if ($user['credits'] < 2) {
        $erreur = "Crédits insuffisants pour proposer un trajet (2 crédits nécessaires).";
    } else {
        $sql_voiture = "INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation, tabac, animal) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_voiture = $pdo->prepare($sql_voiture);
        $stmt_voiture->execute([$modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation, $tabac, $animal]);
        $voiture_id = $pdo->lastInsertId();

        $sql2 = "INSERT INTO covoiturage (lieu_depart, lieu_arrivee, date_depart, heure_depart, date_arrivee, heure_arrivee, nombre_place, prix_personne, voyage_eco, statut, voiture_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'actif', ?)";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$lieu_depart, $lieu_arrivee, $date_depart, $heure_depart, $date_arrivee, $heure_arrivee, $nombre_place, $prix_personne, $voyage_eco, $voiture_id]);

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
                <hr>
                <h5 style="color: #248179;" class="mb-3">Informations du véhicule</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Plaque d'immatriculation</label>
                    <input type="text" name="immatriculation" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Date de première immatriculation</label>
                    <input type="date" name="date_premiere_immatriculation" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label>Modèle</label>
                    <input type="text" name="modele" class="form-control" required>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Marque</label>
                    <input type="text" name="marque" class="form-control" required>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Couleur</label>
                    <input type="text" name="couleur" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Énergie</label>
                    <select name="energie" class="form-control" required>
                      <option value="Essence">Essence</option>
                      <option value="Diesel">Diesel</option>
                      <option value="Électrique">Électrique</option>
                      <option value="Hybride">Hybride</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <div class="form-check">
                      <input type="checkbox" name="tabac" class="form-check-input" id="tabac">
                      <label class="form-check-label" for="tabac">🚬 Fumeur accepté</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="form-check">
                      <input type="checkbox" name="animal" class="form-check-input" id="animal">
                      <label class="form-check-label" for="animal">🐾 Animaux acceptés</label>
                    </div>
                  </div>
                </div>
                <hr>
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