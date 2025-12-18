# Script para crear todas las carpetas de imágenes necesarias
# Ejecutar desde: c:\xampp\htdocs\perunet

$baseDir = "c:\xampp\htdocs\perunet\public\img"

# Crear carpetas si no existen
$folders = @(
    # ALMACENAMIENTO
    "$baseDir\ALMACENAMIENTO\Discos Duros Externos",
    "$baseDir\ALMACENAMIENTO\Discos HDD",
    "$baseDir\ALMACENAMIENTO\Discos SSD",
    "$baseDir\ALMACENAMIENTO\Memorias SD",
    "$baseDir\ALMACENAMIENTO\Memorias USB",
    
    # CABLADO
    "$baseDir\CABLADO\Cables Contra Incendios",
    "$baseDir\CABLADO\Canaletas",
    "$baseDir\CABLADO\RJ45",
    "$baseDir\CABLADO\PATCH",
    "$baseDir\CABLADO\UTP",
    
    # COMPONENTES (para el builder)
    "$baseDir\COMPONENTES\PROCESADORES",
    "$baseDir\COMPONENTES\PLACAS",
    "$baseDir\COMPONENTES\RAM",
    "$baseDir\COMPONENTES\GPU",
    "$baseDir\COMPONENTES\FUENTE",
    "$baseDir\COMPONENTES\CASE",
    
    # CONTROL DE ACCESO
    "$baseDir\CONTROL DE ACCESO\CERRADURAS",
    "$baseDir\CONTROL DE ACCESO\INTERCOMUNICADORES",
    "$baseDir\CONTROL DE ACCESO\LECTORES",
    "$baseDir\CONTROL DE ACCESO\MODULOS",
    "$baseDir\CONTROL DE ACCESO\TAGS",
    
    # GAMER
    "$baseDir\GAMER\AUDIFONOS",
    "$baseDir\GAMER\ESCRITORIOS",
    "$baseDir\GAMER\mouse",
    "$baseDir\GAMER\PADMOUSE",
    "$baseDir\GAMER\PARLANTES",
    "$baseDir\GAMER\SILLAS",
    "$baseDir\GAMER\TECLADOS",
    
    # VIDEOVIGILANCIA
    "$baseDir\VIDEOVIGILANCIA\ACCESORIOS DE VIGILANCIA",
    "$baseDir\VIDEOVIGILANCIA\Alarmas",
    "$baseDir\VIDEOVIGILANCIA\CAMARAS",
    "$baseDir\VIDEOVIGILANCIA\MONITORES",
    "$baseDir\VIDEOVIGILANCIA\NVR"
)

Write-Host "Creando carpetas de imágenes..." -ForegroundColor Cyan
Write-Host ""

$created = 0
$existing = 0

foreach ($folder in $folders) {
    if (-not (Test-Path $folder)) {
        New-Item -ItemType Directory -Path $folder -Force | Out-Null
        Write-Host "[CREADA] $folder" -ForegroundColor Green
        $created++
    } else {
        Write-Host "[EXISTE] $folder" -ForegroundColor Yellow
        $existing++
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Resumen:" -ForegroundColor White
Write-Host "  Carpetas creadas: $created" -ForegroundColor Green
Write-Host "  Carpetas existentes: $existing" -ForegroundColor Yellow
Write-Host "  Total: $($created + $existing)" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "¡Listo! Ahora puedes colocar las imágenes en sus carpetas correspondientes." -ForegroundColor Green
Write-Host ""
Write-Host "Ejemplo:" -ForegroundColor White
Write-Host "  Producto: Seagate Expansion 1TB" -ForegroundColor Gray
Write-Host "  Carpeta: $baseDir\ALMACENAMIENTO\Discos Duros Externos\" -ForegroundColor Gray
Write-Host "  Archivo: Seagate Expansion 1TB.png" -ForegroundColor Gray
