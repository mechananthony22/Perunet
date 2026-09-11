# PeruNet - Guia de Pentesting

## File Upload - Webshell (A05)
Subida de imagenes en admin productos sin validacion (getimagesize comentado).
Crear un PHP con system() y subir como imagen.
/ruta: Admin/ProductosController.php:158-163

## Resumen
SQLi: Productos (ordenar), API Usuario, Cupones, Contacto
XSS Stored: Contacto -> admin/mensajes
File Upload: Admin productos (webshell)
IDOR: /usuarios/compra/:id
Exposicion datos: API usuario, exportacion admin
Mass Assignment: registro con id_rol=1
Open Redirect: /redirect?url=
phpinfo(): /phpinfo
Session Hijacking: httponly=false (App.php)
