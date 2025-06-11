<!-- Modal Login/Register -->
<div class="modal fade" id="login-form" tabindex="-1" aria-labelledby="loginFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="loginFormLabel">Accede o Regístrate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">

                <!-- Nav Tabs -->
                <ul class="nav nav-pills justify-content-center mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-controls="pills-login" aria-selected="true">
                            Iniciar Sesión
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab" aria-controls="pills-register" aria-selected="false">
                            Registrarse
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">

                    <!-- Iniciar Sesión -->
                    <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
                        <form action="/auth/login.php" method="POST">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                            <div class="text-center mt-2">
                                <a href="/auth/forgot-password.php">¿Olvidaste tu contraseña?</a>
                            </div>
                        </form>
                    </div>

<!-- Registrarse -->
<div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="pills-register-tab">
    <form action="/auth/registrar_afiliado.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombreRegistro" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombreRegistro" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="emailRegistro" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="emailRegistro" name="email" required>
        </div>
        <div class="mb-3">
            <label for="telefonoRegistro" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefonoRegistro" name="telefono">
        </div>
        <div class="mb-3">
            <label for="passwordRegistro" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="passwordRegistro" name="password" required>
        </div>
        <div class="mb-3">
            <label for="slugRegistro" class="form-label">Nombre para micrositio</label>
            <input type="text" class="form-control" id="slugRegistro" name="slug" placeholder="ej. juan-yates" required>
        </div>
        <div class="mb-3">
            <label for="ineRegistro" class="form-label">Foto de INE (opcional)</label>
            <input type="file" class="form-control" id="ineRegistro" name="foto_ine" accept="image/*">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Registrarse</button>
        </div>
    </form>
</div>

                </div>
            </div>
        </div>
    </div>
</div>
