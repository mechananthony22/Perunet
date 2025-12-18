<?php
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/SubcategoriasModel.php';

class ProductosController extends Controller
{
    private $productoModel;
    private $categoriasModel;
    private $subcategoriasModel;

    public function __construct()
    {
        parent::__construct();
        $this->productoModel = new ProductoModel();
        $this->categoriasModel = new CategoriasModel();
        $this->subcategoriasModel = new SubcategoriasModel();
    }

    public function busqueda()
    {
        // Obtener término de búsqueda
        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
        
        // Obtener filtros de la URL
        $marcas = isset($_GET['marca']) ? (array)$_GET['marca'] : [];
        $precioMin = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : null;
        $precioMax = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : null;

        // Si no hay búsqueda, redirigir al inicio
        if (empty($busqueda)) {
            $this->redirect('/');
            return;
        }

        // Buscar productos
        $productos = $this->productoModel->buscar($busqueda, $marcas, $precioMin, $precioMax);
        
        // Obtener todas las categorías para el filtro
        $categorias = $this->categoriasModel->getAll();
        
        // Obtener estadísticas del carrito
        $cartCount = cartCount();
        
        $this->renderWithLayout('productos/busqueda', [
            'title' => 'Búsqueda: ' . htmlspecialchars($busqueda) . ' - PeruNet',
            'description' => 'Resultados de búsqueda para: ' . htmlspecialchars($busqueda),
            'productos' => $productos,
            'busqueda' => $busqueda,
            'categorias' => $categorias,
            'cartCount' => $cartCount,
            'session' => $this->session
        ]);
    }

    public function indexCategoria($categoria)
    {
        // Obtener filtros de la URL
        $marcas = isset($_GET['marca']) ? (array)$_GET['marca'] : [];
        $precioMin = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : null;
        $precioMax = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : null;

        // Obtener información de la categoría
        $categoriaInfo = $this->categoriasModel->getBySlug($categoria);
        
        if (!$categoriaInfo) {
            $this->redirect('/404');
        }
        
        // Obtener productos de la categoría
        $categoriaId = isset($categoriaInfo['id']) ? $categoriaInfo['id'] : (isset($categoriaInfo['id_cat']) ? $categoriaInfo['id_cat'] : null);
        if ($categoriaId === null) {
            $this->redirect('/404');
        }
        $productos = $this->productoModel->getByCategory($categoriaId, $marcas, $precioMin, $precioMax);
        
        // Obtener subcategorías de esta categoría
        $subcategorias = $this->subcategoriasModel->getByCategory($categoriaId);
        // Normalizar estructura
        $subcategorias = array_map(function($s) {
            return is_array($s) && isset($s['nombre']) ? $s : ['nombre' => is_array($s) ? ($s['nombre'] ?? '') : $s];
        }, $subcategorias);
        
        // Obtener estadísticas del carrito
        $cartCount = cartCount();
        
        $this->renderWithLayout('productos/index', [
            'title' => $categoriaInfo['nombre'] . ' - PeruNet',
            'description' => 'Productos de ' . $categoriaInfo['nombre'],
            'productos' => $productos,
            'categoria' => $categoriaInfo,
            'subcategorias' => $subcategorias,
            'cartCount' => $cartCount,
            'session' => $this->session,
            'nombreCategoria' => $categoriaInfo['nombre'],
            'nombreSubcategoria' => null
        ]);
    }

    public function indexSubcategoria($categoria, $subcategoria)
    {
        // Obtener filtros de la URL
        $marcas = isset($_GET['marca']) ? (array)$_GET['marca'] : [];
        $precioMin = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : null;
        $precioMax = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : null;

        // Debug temporal
        error_log('Slug recibido: categoria=' . $categoria . ' | subcategoria=' . $subcategoria);
        // Obtener información de la categoría
        $categoriaInfo = $this->categoriasModel->getBySlug($categoria);
        
        if (!$categoriaInfo) {
            error_log('Categoría no encontrada para slug: ' . $categoria);
            $this->redirect('/404');
        }
        
        // Obtener información de la subcategoría
        $catId = isset($categoriaInfo['id']) ? $categoriaInfo['id'] : (isset($categoriaInfo['id_cat']) ? $categoriaInfo['id_cat'] : null);
        $subcategoriaInfo = $this->subcategoriasModel->getBySlug($subcategoria, $catId);
        
        if (!$subcategoriaInfo) {
            error_log('Subcategoría no encontrada para slug: ' . $subcategoria . ' en categoría ID: ' . $catId);
            $this->redirect('/404');
        }
        
        // Obtener productos de la subcategoría
        $subcategoriaId = isset($subcategoriaInfo['id']) ? $subcategoriaInfo['id'] : null;
        if ($subcategoriaId === null) {
            $this->redirect('/404');
        }
        $productos = $this->productoModel->getBySubcategory($subcategoriaId, $marcas, $precioMin, $precioMax);
        
        // Obtener subcategorías de esta categoría
        $subcategorias = $this->subcategoriasModel->getByCategory($catId);
        // Normalizar estructura
        $subcategorias = array_map(function($s) {
            return is_array($s) && isset($s['nombre']) ? $s : ['nombre' => is_array($s) ? ($s['nombre'] ?? '') : $s];
        }, $subcategorias);
        
        // Obtener estadísticas del carrito
        $cartCount = cartCount();
        
        $this->renderWithLayout('productos/index', [
            'title' => $subcategoriaInfo['nombre'] . ' - ' . $categoriaInfo['nombre'] . ' - PeruNet',
            'description' => 'Productos de ' . $subcategoriaInfo['nombre'],
            'productos' => $productos,
            'categoria' => $categoriaInfo,
            'subcategoria' => $subcategoriaInfo,
            'cartCount' => $cartCount,
            'session' => $this->session,
            'nombreCategoria' => $categoriaInfo['nombre'],
            'nombreSubcategoria' => $subcategoriaInfo['nombre']
        ]);
    }
}
