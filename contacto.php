<?php include 'templates/header.php'; ?>

<section class="hero-section text-white text-center d-flex align-items-center justify-content-center" style="background: url('assets/imgs/contacto-hero.jpg') center center/cover no-repeat; min-height: 50vh;">
  <div class="container">
    <h1 class="display-5 fw-bold" style="color:#fff;">Contáctanos</h1>
    <p class="lead" style="color:#fff;">¿Tienes dudas? ¡Estamos para ayudarte!</p>
  </div>
</section>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-6">
        <h2 class="mb-4">Envíanos un mensaje</h2>
        <form method="post" action="mailto:info@rentayatecancun.com" enctype="text/plain">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea class="form-control" name="mensaje" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Enviar mensaje</button>
        </form>
      </div>

      <div class="col-lg-6">
        <h2 class="mb-4">Información de contacto</h2>
        <ul class="list-unstyled">
  <li><strong>📍 Dirección:</strong> Marina Sunset, Zona Hotelera, Cancún</li>
  <li><strong>📞 Teléfono:</strong> <a href="tel:+529844273666">+52 984 427 3666</a></li>
  <li><strong>📧 Email:</strong> <a href="mailto:info@rentayatecancun.com">info@rentayatecancun.com</a></li>
  <li><strong>💬 WhatsApp:</strong> <a href="https://wa.me/529844273666" target="_blank">Chatea con nosotros por WhatsApp</a></li>
  <li><strong>📱 Redes sociales:</strong><br>
    <a href="https://instagram.com/rentayatecancun" target="_blank">Instagram</a> |
    <a href="https://www.facebook.com/profile.php?id=61575025084947#" target="_blank">Facebook</a> |
    <a href="https://tiktok.com/@rentayatecancun" target="_blank">TikTok</a>
  </li>
</ul>


        <div class="mt-4">
          <iframe src="https://www.google.com/maps?q=marina+sunset+cancun&output=embed" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="reservar.php" class="btn btn-success btn-lg">Ir a Reservar</a>
    </div>
  </div>
</section>

<?php include 'templates/footer.php'; ?>
