PROYECTO: Sistema de Reservas y Afiliados – Yachts System
Versión: 1.0 (Estructura Base)
Fecha de inicio: Marzo 2025
Autor: Carlos Kantun

---

DESCRIPCIÓN GENERAL
--------------------
Sistema de reservas para experiencias en yate, con registro de afiliados (brokers/promotores), asignación de comisiones, control de asistencia, penalizaciones, ventas internas y paneles por rol.

---

ESTRUCTURA DE CARPETAS
-----------------------
/prestige-system/
│
├── index.php                          → Punto de entrada general del sistema
│
├── config/
│   └── database.php                   → Conexión PDO a base de datos
│
├── core/
│   ├── funciones.php                  → Funciones reutilizables
│   └── auth.php                       → Validación de sesión y roles
│
├── assets/
│   ├── css/                           → Estilos
│   ├── js/                            → Scripts
│   └── imgs/                          → QRs, logos, etc.
│
├── auth/
│   ├── login.php                      → Formulario de inicio de sesión
│   ├── logout.php                     → Cierre de sesión
│   └── registrar_afiliado.php        → Registro de afiliados
│
├── panel_admin/
│   ├── dashboard.php
│   ├── usuarios.php                   → Vista de todos los usuarios
│   ├── aprobar_afiliados.php         → Validación manual
│   ├── salidas.php                    → Gestión de tours
│   ├── reservas.php                   → Gestión global de reservas
│   ├── pagos.php                      → Control de pagos y comisiones
│   ├── descuentos.php                 → Vista de descuentos aplicados
│   ├── penalizaciones.php            → Registro de penalizaciones
│   ├── productos.php                  → Productos para ventas internas
│   └── configuracion.php             → Valores globales (sistema)
│
├── panel_afiliado/
│   ├── dashboard.php
│   ├── mis_reservas.php
│   ├── generar_link.php
│   └── configuracion_micrositio.php  → Datos de micrositio (slug, logo, etc.)
│
├── panel_staff/
│   ├── salidas_asignadas.php
│   ├── ventas.php                    → Registro de ventas internas
│   └── checklist.php                → Confirmación de asistencia
│
└── templates/
    ├── header.php
    ├── footer.php
    └── navbar.php

---

BASE DE DATOS
-------------
Nombre: prestige_sistema
Usuario: prestige_sysadmiin
Contraseña: +1]kx*BGrWwq

Tablas principales:
- usuarios
- afiliados (con micrositio: slug, logo, descripción, redes)
- salidas
- reservas
- cupones
- pagos (con afiliado_id para distinguir venta directa vs afiliado)
- descuentos
- penalizaciones
- ventas_internas
- configuracion_sistema (parametrizable desde el panel)

Configuraciones cargadas por default:
- registro_afiliado_manual = true
- porcentaje_penalizacion = 30
- comision_afiliado_default = 40
- precio_base_reserva = 70
- costo_base_empresa = 30
- modo_pago_automatico = false
- descuento_maximo = 15
- moneda_sistema = USD

---

FLUJOS CONFIRMADOS
------------------
- Registro de afiliado (pendiente de aprobación si config está en manual)
- Panel de administración con aprobación de afiliados
- Micrositio previsto por afiliado (campos ya creados)
- Descuentos aplicables con control de límite
- Confirmación de asistencia por parte del moderador o staff
- Penalizaciones automáticas o manuales
- Registro de productos internos (bebidas, fotos, etc.)
- Control de ventas internas por staff desde su panel

---

PRÓXIMOS PASOS
--------------
- Usar variables de entorno para las credenciales de la base de datos.
- Agregar pruebas automáticas para validar el código.
- Incluir un archivo .gitignore y limpiar logs versionados.

---

OBSERVACIONES
-------------
Todo el sistema está modularizado para escalarse a futuro.
Paneles diferenciados por rol.
Listo para integrarse visualmente a WordPress como frontend (opcional).
Compatible con pasarela Mercado Pago.

