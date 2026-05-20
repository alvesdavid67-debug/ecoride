<?php
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $erreur = "Les mots de passe ne correspondent pas";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (pseudo, email, password, credits) VALUES (?, ?, ?, 20)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pseudo, $email, $hash]);
        header('Location: connexion.php');
        exit;
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
            <h2 style="color: #248179; font-family: Poppins;" class="text-center mb-4">Créer un compte</h2>
            
            <form method="POST" action="inscription.php">
              <div class="mb-3">
                <label>Pseudo</label>
                <input type="text" name="pseudo" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" class="form-control" required>
              </div>
              <button type="submit" class="btn w-100" style="background-color: #FB9B27; color: white;">
                Créer mon compte
              </button>
            </form>

            <p class="text-center mt-3">
              Déjà un compte ? <a href="connexion.php" style="color: #248179;">Se connecter</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>