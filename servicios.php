<?php include 'templates/header.php'; ?>

<style>
.hero-overlay::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4); /* Ajusta la opacidad aquí */
  z-index: 1;
}
.hero-overlay .container {
  position: relative;
  z-index: 2;
}
</style>

<section class="hero-overlay text-white text-center d-flex align-items-center justify-content-center" style="background: url('assets/imgs/servicios-hero.jpg') center center/cover no-repeat; min-height: 50vh; position: relative;">
  <div class="container">
    <h1 class="display-5 fw-bold" style="color: #fff;">Paquetes y Servicios Adicionales</h1>
    <p class="lead" style="color: #fff;">Haz que tu experiencia en yate sea inolvidable con nuestros extras</p>
  </div>
</section>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="mb-3">Paquetes y Servicios Adicionales</h2>
<p>Mejora tu experiencia en yate con extras personalizados. Puedes reservarlos por adelantado o al abordar.</p>

    </div>

    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="card h-100 border-primary">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">Paquete Premium</h5>
            <p class="text-muted">$120 USD por grupo (hasta 2 personas)</p>
            <ul class="list-unstyled">
                <li>🍸 Bebidas a bordo con botella de champán</li>
                <li>🏊 Nado con snorkel en Blanquizal</li>
                <li>📸 Fotos y video profesional con dron</li>
            </ul>  
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border-success">
          <div class="card-body text-center">
            <h5 class="card-title text-success">Paquete Estándar</h5>
            <p class="text-muted">$75 USD por grupo (hasta 2 personas)</p>
            <ul class="list-unstyled">
                <li>🥤 Bebidas sin alcohol ilimitadas</li>
                <li>🏝️ Nado en Blanquizal</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 border-dark">
          <div class="card-body text-center">
            <h5 class="card-title text-dark">Paquete Personalizado</h5>
            <p class="text-muted">Paga solo lo que quieras usar</p>
            <ul class="list-unstyled">
              <li>🍾 Bebidas y botella de champán: $50 USD por grupo</li>
              <li>🏊 Nado en Blanquizal con snorkel: $50 USD por grupo</li>
              <li>📸 Fotos y video con dron: $50 USD por grupo</li>

            </ul>
          </div>
        </div>
      </div>
    </div>


<div class="text-center mb-5">
  <h2 class="mb-4">Adquiere tu Pasaporte Nautico</h2>
  <p>Con la compra de tu <strong>Pasaporte Nautico</strong>, recupera tu inversión y accedes a un paquete con valor total de más <strong>$1,500 USD</strong>, que incluye:</p>
</div>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <div class="card h-100 border-warning">
          <div class="card-body">
            <h5 class="card-title">Paquete "Pasaporte Náutico"</h5>
            <ul>
            <li> $1,000 USD 🚤 de credito para tu salida en yate privada
            <li> $500 USD 🎟️En  Monedero de crédito vacacional BeJourney</li
            <ol> Con tu Monedero puedes usarlo para hoteles, renta de autos o excursiones.</ol> </li>
            <li> Descuentos en Actividades Acuaticas.</li>
            <li> <p><strong>Reembolso del tour</strong>:<em>$75 USD</em></p> </li>
            <p style="text-align: center;"><strong>$599 USD</strong><br><em>Valor total estimado: Más de $1,500 USD</em></p>
              </ul>
           
          </div>
        </div>
      </div>
    </div>
<div class="text-center mt-5">
  <h4 class="mb-3">🌍 ¿Quieres seguir viajando como VIP?</h4>
  <p class=""></p> 
  <p class="mb-2"  style="text-align: center;" ><strong>Recargar tu monedero</strong>, con tan solo <strong>$25 USD</strong> adicionales, obtén<strong>  $500 USD de crédito extras</strong>. Accede a una semana de vacaciones en cualquier parte del mundo desde 200 USD. Reservar hoteles, tours y experiencias alrededor del mundo.</p>
  <p class="mb-4"><em>Un beneficio exclusivo para quienes viajan con Rentayatecancun.</em></p>
  <a href="https://bejourneywallet.com/" target="_blank" class="btn btn-primary btn-lg">Descubre BeJourney Wallet</a>
</div>

    <div class="text-center mt-5">
      <a href="reservar.php" class="btn btn-primary btn-lg">Reservar Tour</a>
    </div>
  </div>
</section>

<?php include 'templates/footer.php'; ?>
