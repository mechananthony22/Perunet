# 📸 Guía para Agregar Imágenes de Productos

## 📁 Estructura de Carpetas

Las imágenes deben estar en: `c:\xampp\htdocs\perunet\public\img\`

### Estructura Completa Requerida:

```
public/img/
├── ALMACENAMIENTO/
│   ├── Discos Duros Externos/
│   ├── Discos HDD/
│   ├── Discos SSD/
│   ├── Memorias SD/
│   └── Memorias USB/
├── CABLADO/
│   ├── Cables Contra Incendios/
│   ├── Canaletas/
│   ├── RJ45/
│   ├── PATCH/
│   └── UTP/
├── COMPONENTES/
│   ├── PROCESADORES/
│   ├── PLACAS/
│   ├── RAM/
│   ├── GPU/
│   ├── FUENTE/
│   └── CASE/
├── CONTROL DE ACCESO/
│   ├── CERRADURAS/
│   ├── INTERCOMUNICADORES/
│   ├── LECTORES/
│   ├── MODULOS/
│   └── TAGS/
├── GAMER/
│   ├── AUDIFONOS/
│   ├── ESCRITORIOS/
│   ├── mouse/
│   ├── PADMOUSE/
│   ├── PARLANTES/
│   ├── SILLAS/
│   └── TECLADOS/
└── VIDEOVIGILANCIA/
    ├── ACCESORIOS DE VIGILANCIA/
    ├── Alarmas/
    ├── CAMARAS/
    ├── MONITORES/
    └── NVR/
```

---

## 🎯 Cómo Agregar Imágenes

### Opción 1: Crear Carpetas Faltantes Manualmente

1. Abre el explorador de archivos de Windows
2. Navega a: `c:\xampp\htdocs\perunet\public\img\`
3. Crea las carpetas que faltan según la estructura de arriba

### Opción 2: Usar el Script Automático (Recomendado)

Ejecuta el siguiente comando en PowerShell desde la raíz del proyecto:

```powershell
# Navegar a la carpeta del proyecto
cd c:\xampp\htdocs\perunet

# Ejecutar el script de creación de carpetas
.\crear_carpetas_imagenes.ps1
```

---

## 📝 Nombres de Archivos de Imagen

Según tu base de datos, los nombres de las imágenes deben ser **exactamente** como están en la columna `imagen` de la tabla `producto`.

### Ejemplos de nombres correctos:

**ALMACENAMIENTO:**
- `Seagate Expansion 1TB.png`
- `WD My Passport 2TB.png`
- `Kingston A400 480GB.png`
- `Kingston SD 64GB.png`
- `Kingston USB 32GB.png`

**CABLADO:**
- `Cable 50m.png`
- `Cable UTP Cat6 305m.png`
- `Canaleta PVC 1m.png`
- `Conector RJ45 Cat6.png`
- `Patch Panel 24 Puertos.png`

**CONTROL DE ACCESO:**
- `Cerradura WiFi.png`
- `Intercom 7".png`
- `Lector Huella.png`
- `Controlador 2 Puertas.png`
- `Tag 125kHz.png`

**VIDEOVIGILANCIA:**
- `Alarma PIR.png`
- `Sirena 110dB.png`

---

## 🖼️ Formato de Imágenes Recomendado

- **Formato:** PNG (preferido) o JPG
- **Tamaño:** 500x500 px (cuadrado)
- **Fondo:** Blanco o transparente
- **Peso:** Máximo 200 KB por imagen

---

## ⚠️ Importante

1. **Nombres exactos:** Los nombres de archivo deben coincidir **exactamente** con lo que está en la base de datos
2. **Mayúsculas/minúsculas:** Respeta las mayúsculas y minúsculas
3. **Espacios:** Los espacios en los nombres son permitidos
4. **Extensión:** Usa `.png` como está definido en la BD

---

## 🔍 Verificar que las Imágenes se Muestran

1. Coloca las imágenes en sus carpetas correspondientes
2. Abre el navegador
3. Ve a: `http://localhost/perunet/productos/almacenamiento`
4. Deberías ver las imágenes de los productos

Si no se muestran, verifica:
- ✅ Ruta correcta de la carpeta
- ✅ Nombre exacto del archivo
- ✅ Extensión correcta (.png)
- ✅ Permisos de lectura en la carpeta

---

## 📦 Imagen por Defecto

Si una imagen no existe, el sistema mostrará:
`EMPRESA/p.png`

Asegúrate de tener esta imagen como respaldo.

---

## 🚀 Ejemplo Completo

Para el producto: **"Seagate Expansion 1TB USB 3.0"**

1. **Ruta en BD:** `ALMACENAMIENTO/Discos Duros Externos/Seagate Expansion 1TB.png`
2. **Ruta física:** `c:\xampp\htdocs\perunet\public\img\ALMACENAMIENTO\Discos Duros Externos\Seagate Expansion 1TB.png`
3. **URL accesible:** `http://localhost/perunet/public/img/ALMACENAMIENTO/Discos%20Duros%20Externos/Seagate%20Expansion%201TB.png`

---

## 💡 Consejos

1. **Usa imágenes de calidad:** Productos con buenas imágenes venden más
2. **Mantén consistencia:** Todas las imágenes con el mismo tamaño y fondo
3. **Optimiza el peso:** Usa herramientas como TinyPNG para reducir el tamaño
4. **Nombra correctamente:** Evita caracteres especiales excepto espacios y guiones

---

## 🛠️ Solución de Problemas

### Problema: La imagen no se muestra
**Solución:**
1. Verifica que la ruta en la BD coincida con la carpeta física
2. Revisa que el nombre del archivo sea exacto (mayúsculas/minúsculas)
3. Asegúrate que la imagen existe en la carpeta

### Problema: Error 404 en la imagen
**Solución:**
1. Verifica que la carpeta `public/img/` existe
2. Comprueba los permisos de lectura
3. Revisa que la URL esté correctamente formada

### Problema: Imagen se ve pixelada
**Solución:**
1. Usa imágenes de al menos 500x500 px
2. Guarda en formato PNG para mejor calidad
3. No uses imágenes muy pequeñas escaladas

---

**¡Listo! Ahora puedes agregar todas tus imágenes de productos.** 🎉
