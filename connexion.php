<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM utilisateur WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['utilisateur_id'];
        $_SESSION['pseudo'] = $user['pseudo'];
        header('Location: index.php');
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card border-0 shadow-sm p-4">
            <h2 style="color: #248179; font-family: Poppins;" class="text-center mb-4">Se connecter</h2>

            <?php if (isset($erreur)) : ?>
              <div class="alert alert-danger"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form method="POST" action="connexion.php">
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button type="submit" class="btn w-100" style="background-color: #248179; color: white;">
                Se connecter
              </button>
            </form>

            <p class="text-center mt-3">
              Pas encore de compte ? <a href="inscription.php" style="color: #248179;">S'inscrire</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>