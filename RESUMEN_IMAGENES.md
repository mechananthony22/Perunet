# 🎯 Resumen Rápido: Cómo Agregar Imágenes

## ✅ Carpetas Creadas

Todas las carpetas necesarias ya fueron creadas automáticamente en:
`c:\xampp\htdocs\perunet\public\img\`

---

## 📸 Pasos para Agregar Imágenes

### 1️⃣ Descarga o Crea las Imágenes
- Busca imágenes de los productos en internet
- O toma fotos de los productos reales
- Formato recomendado: PNG, 500x500px

### 2️⃣ Renombra las Imágenes
Usa **exactamente** los nombres que están en la base de datos:

| Producto en BD | Nombre de Archivo |
|----------------|-------------------|
| Seagate Expansion 1TB USB 3.0 | `Seagate Expansion 1TB.png` |
| WD My Passport 2TB | `WD My Passport 2TB.png` |
| Kingston A400 480GB | `Kingston A400 480GB.png` |

### 3️⃣ Coloca las Imágenes en sus Carpetas

**Ejemplo 1: Disco Duro Externo**
```
Producto: "Seagate Expansion 1TB USB 3.0"
Ruta BD: ALMACENAMIENTO/Discos Duros Externos/Seagate Expansion 1TB.png
Copiar a: c:\xampp\htdocs\perunet\public\img\ALMACENAMIENTO\Discos Duros Externos\Seagate Expansion 1TB.png
```

**Ejemplo 2: Cable UTP**
```
Producto: "Cable UTP Cat6 305m"
Ruta BD: CABLADO/UTP/Cable UTP Cat6 305m.png
Copiar a: c:\xampp\htdocs\perunet\public\img\CABLADO\UTP\Cable UTP Cat6 305m.png
```

**Ejemplo 3: Cerradura**
```
Producto: "Cerradura Inteligente WiFi"
Ruta BD: CONTROL DE ACCESO/CERRADURAS/Cerradura WiFi.png
Copiar a: c:\xampp\htdocs\perunet\public\img\CONTROL DE ACCESO\CERRADURAS\Cerradura WiFi.png
```

### 4️⃣ Verifica en el Navegador
1. Abre: `http://localhost/perunet`
2. Busca el producto
3. La imagen debería mostrarse

---

## 🗂️ Mapa de Carpetas por Categoría

### ALMACENAMIENTO
- `Discos Duros Externos/` → Discos externos portátiles
- `Discos HDD/` → Discos duros internos
- `Discos SSD/` → Discos sólidos
- `Memorias SD/` → Tarjetas SD
- `Memorias USB/` → Pendrives

### CABLADO
- `Cables Contra Incendios/` → Cables ignífugos
- `Canaletas/` → Canaletas PVC
- `RJ45/` → Conectores RJ45
- `PATCH/` → Patch panels
- `UTP/` → Cables UTP

### CONTROL DE ACCESO
- `CERRADURAS/` → Cerraduras inteligentes
- `INTERCOMUNICADORES/` → Intercomunicadores
- `LECTORES/` → Lectores biométricos
- `MODULOS/` → Controladores
- `TAGS/` → Tags de proximidad

### GAMER
- `AUDIFONOS/` → Audífonos gamer
- `ESCRITORIOS/` → Escritorios gamer
- `mouse/` → Mouses gamer
- `PADMOUSE/` → Pads de mouse
- `PARLANTES/` → Parlantes
- `SILLAS/` → Sillas gamer
- `TECLADOS/` → Teclados

### VIDEOVIGILANCIA
- `ACCESORIOS DE VIGILANCIA/` → Accesorios
- `Alarmas/` → Alarmas y sensores
- `CAMARAS/` → Cámaras IP
- `MONITORES/` → Monitores
- `NVR/` → Grabadores NVR

---

## ⚡ Atajos Rápidos

### Abrir Carpeta de Imágenes
```
Windows + R
Pegar: c:\xampp\htdocs\perunet\public\img
Enter
```

### Ver Imagen en Navegador
```
http://localhost/perunet/public/img/ALMACENAMIENTO/Discos%20Duros%20Externos/Seagate%20Expansion%201TB.png
```

---

## 🎨 Recomendaciones de Diseño

1. **Fondo blanco o transparente**
2. **Producto centrado**
3. **Sin texto adicional** (el nombre ya está en la BD)
4. **Alta calidad** pero peso optimizado (<200KB)
5. **Mismo tamaño** para todas (500x500px)

---

## 🚨 Errores Comunes

❌ **Nombre incorrecto**
```
Archivo: seagate expansion 1tb.png (minúsculas)
Correcto: Seagate Expansion 1TB.png
```

❌ **Carpeta incorrecta**
```
Ubicación: ALMACENAMIENTO/Seagate Expansion 1TB.png
Correcto: ALMACENAMIENTO/Discos Duros Externos/Seagate Expansion 1TB.png
```

❌ **Extensión incorrecta**
```
Archivo: Seagate Expansion 1TB.jpg
Correcto: Seagate Expansion 1TB.png
```

---

## 📝 Lista de Verificación

- [ ] Carpetas creadas (ya hecho ✅)
- [ ] Imágenes descargadas/creadas
- [ ] Imágenes renombradas correctamente
- [ ] Imágenes copiadas a sus carpetas
- [ ] Verificado en el navegador

---

**¡Ahora solo falta copiar las imágenes!** 🎉

Para más detalles, consulta: `GUIA_IMAGENES.md`
