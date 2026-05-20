<?php
session_start();
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Statistiques nombre total de trajets
$sql_trajets = "SELECT COUNT(*) as total FROM covoiturage";
$stmt_trajets = $pdo->prepare($sql_trajets);
$stmt_trajets->execute();
$total_trajets = $stmt_trajets->fetch()['total'];

$sql_users = "SELECT COUNT(*) as total FROM utilisateur";
$stmt_users = $pdo->prepare($sql_users);
$stmt_users->execute();
$total_users = $stmt_users->fetch()['total'];

$sql_reservations = "SELECT COUNT(*) as total FROM reservation WHERE statut = 'confirmée'";
$stmt_reservations = $pdo->prepare($sql_reservations);
$stmt_reservations->execute();
$total_reservations = $stmt_reservations->fetch()['total'];

// Liste des utilisateurs
$sql_liste = "SELECT * FROM utilisateur ORDER BY utilisateur_id DESC";
$stmt_liste = $pdo->prepare($sql_liste);
$stmt_liste->execute();
$utilisateurs = $stmt_liste->fetchAll();

// Suspendre un compte crédits à 0, plus de résa ou de proposition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspendre'])) {
    $id_suspend = $_POST['user_id'];
    $sql_suspend = "UPDATE utilisateur SET credits = 0 WHERE utilisateur_id = ?";
    $stmt_suspend = $pdo->prepare($sql_suspend);
    $stmt_suspend->execute([$id_suspend]);
    header('Location: admin.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <h2 style="color: #248179; font-family: Poppins;" class="mb-4">Espace Administrateur</h2>

      <!-- STATISTIQUES -->
      <div class="row mb-5">
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm p-4 text-center">
            <h1 style="color: #248179;"><?php echo $total_trajets; ?></h1>
            <p style="color: #4E4F59;">Total des trajets</p>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm p-4 text-center">
            <h1 style="color: #248179;"><?php echo $total_users; ?></h1>
            <p style="color: #4E4F59;">Utilisateurs inscrits</p>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm p-4 text-center">
            <h1 style="color: #248179;"><?php echo $total_reservations; ?></h1>
            <p style="color: #4E4F59;">Réservations confirmées</p>
          </div>
        </div>
      </div>

      <!-- LISTE UTILISATEURS -->
      <h4 style="color: #248179;" class="mb-3">Gestion des utilisateurs</h4>
      <div class="table-responsive">

        <!--SCROLL MOBILE-->
        <table class="table table-striped bg-white">
          <thead style="background-color: #248179; color: white;">
            <tr>
              <th>ID</th>
              <th>Pseudo</th>
              <th>Email</th>
              <th>Crédits</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($utilisateurs as $u) : ?>
              <tr>
                <td><?php echo $u['utilisateur_id']; ?></td>
                <td><?php echo $u['pseudo']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['credits']; ?></td>
                <td>
                  <form method="POST" action="admin.php" class="d-inline">
                    <input type="hidden" name="user_id" value="<?php echo $u['utilisateur_id']; ?>">
                    <button type="submit" name="suspendre" class="btn btn-sm" 
                            style="background-color: #FB9B27; color: white;"
                            onclick="return confirm('Suspendre ce compte ?')">
                      Suspendre
                      <!--POPUP ACTIVE JS-->
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>