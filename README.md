# Yacht Reservation System

Este repositorio contiene el código del sistema de reservas y afiliados utilizado en Rentayate Cancún. Incluye paneles separados por rol, generación de códigos QR para afiliados y pago a través de Mercado Pago.

La descripción detallada de carpetas y módulos se encuentra en `README.txt`.

## Instalación rápida
1. Clona este repositorio.
2. Configura la conexión a base de datos en `config/database.php`. Se recomienda usar variables de entorno en lugar de credenciales en texto plano.
3. Coloca los archivos en un servidor con PHP y abre `index.php` en el navegador.

## Estado actual
Todos los módulos principales listados en `README.txt` se encuentran implementados: conexión a base de datos, funciones básicas, registro e inicio de sesión de afiliados y proceso de reserva con generación de QR.

## Próximos pasos
- Mover credenciales a variables de entorno.
- Agregar pruebas automáticas y un `.gitignore` para archivos temporales.


