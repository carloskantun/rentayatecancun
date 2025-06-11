<?php include 'templates/header.php'; ?>

<section class="hero-section text-white text-center d-flex align-items-center justify-content-center" style="background: url('assets/img/faq-hero.jpg') center center/cover no-repeat; min-height: 50vh;">
  <div class="container">
    <h1 class="display-5 fw-bold">Preguntas Frecuentes</h1>
    <p class="lead">Todo lo que necesitas saber antes, durante y después del tour</p>
  </div>
</section>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="accordion" id="faqAccordion">

      <!-- 1. Reservas -->
      <h4 class="mb-3 mt-5">Reservaciones y Disponibilidad</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">¿Cómo puedo reservar mi lugar?</button>
        </h2>
        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Puedes reservar en nuestra web, redes sociales o con un asesor. ¡Asegura tu cupo pronto!</div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="heading2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">¿Por qué debo reservar con anticipación?</button>
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Las salidas son para 10 personas máximo. Reservando con tiempo aseguras tu lugar.</div>
        </div>
      </div>

      <!-- 2. Cancelaciones -->
      <h4 class="mb-3 mt-5">Cancelaciones y Cambios</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading6">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">¿Puedo cancelar o reprogramar sin perder mi dinero?</button>
        </h2>
        <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Sí. Si cancelas con al menos 24h, puedes reprogramar sin penalización.</div>
        </div>
      </div>

      <!-- 3. Experiencia en el Yate -->
      <h4 class="mb-3 mt-5">Experiencia en el Yate</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading9">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9">¿Qué incluye el tour de $70 USD?</button>
        </h2>
        <div id="collapse9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Incluye navegación de 2 horas para 2 personas. Extras se adquieren por separado.</div>
        </div>
      </div>

      <!-- 4. Experiencia Privada -->
      <h4 class="mb-3 mt-5">Experiencia Privada de Lujo</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading13">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse13">¿Qué beneficios tiene la experiencia privada VIP?</button>
        </h2>
        <div id="collapse13" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Atención personalizada, catering a bordo, grabación con dron y música a tu gusto.</div>
        </div>
      </div>

      <!-- 5. Métodos de Pago -->
      <h4 class="mb-3 mt-5">Pagos y Costos Adicionales</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading16">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse16">¿Qué es el dock fee?</button>
        </h2>
        <div id="collapse16" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Es un impuesto portuario obligatorio de $15-20 USD por persona, pagado en la marina.</div>
        </div>
      </div>

      <!-- 6. Post-Tour -->
      <h4 class="mb-3 mt-5">Después del Tour</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading19">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse19">¿Recibiré fotos o video?</button>
        </h2>
        <div id="collapse19" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Si contrataste el servicio de dron, recibirás tu video profesional después del tour.</div>
        </div>
      </div>

      <!-- 7. Para Afiliados -->
      <h4 class="mb-3 mt-5">Afiliados y Comisiones</h4>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading21">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse21">¿Sobre qué monto se calcula mi comisión?</button>
        </h2>
        <div id="collapse21" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Las comisiones se calculan sobre $40 USD por reserva. Los otros $30 USD corresponden a la operación.</div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="heading22">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse22">¿Qué pasa si no confirmo checklist de mi cliente?</button>
        </h2>
        <div id="collapse22" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">Podrás recibir penalización o descuento de comisión. Se considera falta grave si el cliente no asiste.</div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'templates/footer.php'; ?>
