
<?php include 'includes/header.php'; ?>
<main>

  <!-- HERO -->
  <section style="background-image: url('/ecoride/assets/images/hero.webp'); background-size: cover; background-position: center; min-height: 500px;">
    <div class="container text-center">
      <h1 style="font-family: Poppins; color: #4E4F59;">Voyagez écolo, voyagez ensemble</h1>
      <p style="font-family: Inter; color: #4E4F59;">EcoRide est la première plateforme française de covoiturage écologique.</p>

      <!-- FORMULAIRE DE RECHERCHE -->
      <div class="row justify-content-center mt-4">
        <div class="col-md-3">
          <input type="text" class="form-control" placeholder="Départ" id="depart">
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control" placeholder="Arrivée" id="arrivee">
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" id="date">
        </div>
        <div class="col-md-2">
          <button class="btn w-100" style="background-color: #FB9B27; color: white;">Rechercher</button>
        </div>
      </div>

    </div>
  </section>

  <!-- PRÉSENTATION -->
  <section style="background-color: #FED8A9;" class="py-5">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4">
          <div class="card p-3 border-0 shadow-sm">
            <div class="card-body">
              <p style="font-size: 2rem;">🌿</p>
              <h5 style="color: #248179;">Écologique</h5>
              <p>Privilégiez les véhicules électriques</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3 border-0 shadow-sm">
            <div class="card-body">
              <p style="font-size: 2rem;">💰</p>
              <h5 style="color: #248179;">Économique</h5>
              <p>Partagez les frais de trajet</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3 border-0 shadow-sm">
            <div class="card-body">
              <p style="font-size: 2rem;">👌</p>
              <h5 style="color: #248179;">Simple</h5>
              <p>Réservez en quelques clics</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>