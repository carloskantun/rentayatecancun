<footer class="footer-wrapper text-white footer-layout4 pt-5">
    <div class="widget-area">
        <div class="container">
            <!-- Newsletter -->
            <div class="newsletter-area pb-4 border-bottom border-secondary">
                <div class="row gy-4 justify-content-between align-items-center">
                    <div class="col-lg-5">
                        <h2 class="newsletter-title text-capitalize mb-0" style="color:#fff;">¿Quieres recibir promociones?</h2>
                        <p class="text-white-50">Suscríbete a nuestro boletín y entérate de las mejores ofertas.</p>
                    </div>
                    <div class="col-lg-7">
                        <form class="newsletter-form style4" action="#" method="POST">
                            <input class="form-control" type="email" placeholder="Ingresa tu correo" required>
                            <button type="submit" class="th-btn style5">Suscribirme <img src="assets/img/icon/plane.svg" alt=""></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer Main -->
            <div class="row mt-4 gy-4 justify-content-between" >
                <div class="col-md-6 col-xl-3">
                    <div class="widget footer-widget">
                        <div class="th-widget-about">
                            <div class="about-logo mb-3">
                                <a href="index.php"><img src="assets/imgs/rentayatelogob.png" alt="Renta Yate Cancun"></a>
                            </div>
                            <p style="color:#fff;"><form method="get" action="/ver_reserva.php">
  <div class="input-group">
    <input type="text" name="codigo" class="form-control" placeholder="Ingresa tu código RYC-XXXXXX" required>
    <button class="btn btn-primary" type="submit">Consultar</button>
  </div>
</form>
</p>
                            <div class="th-social mt-3">
                                <a href="https://www.facebook.com/profile.php?id=61575025084947#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/rentayatecancun/?fbclid=IwZXh0bgNhZW0CMTEAYnJpZBExTWdIVnBleEg2T2ZEWGNZaQEe102Aw8YgFqn2xJnXQzx5lRYkKpI6jk1Wrsb7aMjf5POoSLH4V08t51GUms0_aem_x6XeV08EJFCUQa0LkUxmNw" target="_blank"><i class="fab fa-instagram"></i></a>
                                <a href="https://wa.me/529844273666" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-auto">
                    <div class="widget footer-widget">
                        <h3 class="widget_title">Enlaces Rápidos</h3>
                        <ul class="menu list-unstyled">
                            <li><a href="/index.php">Inicio</a></li>
                            <li><a href="/reservar.php">Reservar</a></li>
                            <li><a href="/servicios.php">Servicios</a></li>
                            <li><a href="/contacto.php">Contáctanos</a></li>
                            <li><a href="/afiliate.php">Afíliate</a></li>
                            <li><a href="/aviso.php">Aviso de Privacidad</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-xl-auto">
                    <div class="widget footer-widget">
                        <h3 class="widget_title">Contáctanos</h3>
                        <div class="th-widget-contact">
                            <p><i class="fa fa-phone me-2"></i><a href="tel:+529844273666" class="text-white">+52 984 4273666</a></p>
                            <p><i class="fa fa-envelope me-2"></i><a href="mailto:contacto@rentayatecancun.com" class="text-white">contacto@rentayatecancun.com</a></p>
                            <p style="color:#fff;"><i class="fa fa-location-dot me-2"></i>Zona Hotelera Cancún</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="copyright-wrap border-top border-secondary mt-4 pt-3 pb-3">
        <div class="container text-center">
            <p class="mb-0" style="color:#fff;">&copy; 2025 Rentayatecancún. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Button -->
<a href="https://wa.me/529844273666" class="btn btn-success position-fixed rounded-circle shadow"
   style="bottom: 20px; right: 20px; z-index: 999;" target="_blank" title="¿Necesitas ayuda?">
    <i class="fab fa-whatsapp fa-lg"></i>
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="/assets/js/script.js"></script>
<!-- JS: Bootstrap y Swiper -->
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/swiper-bundle.min.js"></script>
<!-- ThemeHoly JS (si ya viene en tu plantilla original) -->
<script src="/assets/js/main.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".th-menu-toggle");
    const menu = document.querySelector(".main-menu");

    if (toggle && menu) {
      toggle.addEventListener("click", function () {
        menu.classList.toggle("show-mobile");
      });
    }
  });
</script>

<style>
  /* Estilos móviles del menú */
  .main-menu.show-mobile {
    display: block !important;
    position: absolute;
    top: 100%;
    right: 0;
    background: #ffffff;
    padding: 1rem;
    z-index: 999;
  }
.main-menu ul li a {
  color: #002E5D; /* Azul profundo */
}

  @media (max-width: 1199px) {
    .main-menu {
      display: none !important;
    }
  }
</style>

</body>
</html>
