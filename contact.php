<?php
session_start();
include 'includes/header.php';
?>

<main>
  <section style="background-color: #FED8A9; min-height: 100vh;" class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm p-4">
            <h2 style="color: #248179; font-family: Poppins;" class="text-center mb-4">Contactez-nous</h2>

            <?php if (isset($succes)) : ?>
              <div class="alert alert-success">Message envoyé avec succès !</div>
            <?php endif; ?>

            <form method="POST" action="contact.php">
              <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="5" required></textarea>
              </div>
              <button type="submit" class="btn w-100" style="background-color: #248179; color: white;">
                Envoyer
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>