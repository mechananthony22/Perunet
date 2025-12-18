<nav id="menu" class="bg-gray-50 border-r border-gray-200 flex flex-col h-[calc(100vh-4rem)] mt-3 fixed max-md:hidden w-56 shadow-sm transition-transform duration-500">
    <ul class="flex flex-col gap-1 py-2 flex-1 overflow-y-auto">
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/" class="text-gray-700 font-medium">Perfil</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/usuarios" class="text-gray-700 font-medium">Usuarios</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/productos" class="text-gray-700 font-medium">Productos</a></li>
        
        <!-- Ventas Collapsible -->
        <li class="px-6 py-2 bg-blue-50 rounded-xl transition w-full cursor-pointer" onclick="toggleSubmenu('ventas-submenu', this)">
            <div class="flex justify-between items-center">
                <span class="text-blue-700 font-semibold">Ventas</span>
                <i class="fa fa-chevron-down text-blue-700 text-xs transition-transform duration-300"></i>
            </div>
            <ul id="ventas-submenu" class="ml-4 mt-2 hidden space-y-1 border-l-2 border-blue-200 pl-2">
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/ventas" class="text-gray-600 text-sm block">Listado</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/ventas/reporte" class="text-gray-600 text-sm block">Reporte</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/ventas/resumen" class="text-gray-600 text-sm block">Resumen</a></li>
            </ul>
        </li>

        <!-- Configuración Collapsible -->
        <li class="px-6 py-2 bg-blue-50 rounded-xl transition w-full cursor-pointer" onclick="toggleSubmenu('config-submenu', this)">
            <div class="flex justify-between items-center">
                <span class="text-blue-700 font-semibold">Configuración</span>
                <i class="fa fa-chevron-down text-blue-700 text-xs transition-transform duration-300"></i>
            </div>
            <ul id="config-submenu" class="ml-4 mt-2 hidden space-y-1 border-l-2 border-blue-200 pl-2">
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/config/roles" class="text-gray-600 text-sm block">Roles</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/config/marcas" class="text-gray-600 text-sm block">Marcas</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/config/modelos" class="text-gray-600 text-sm block">Modelos</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/config/categorias" class="text-gray-600 text-sm block">Categorias</a></li>
                <li class="hover:bg-blue-100 px-2 py-1 rounded transition"><a href="/perunet/admin/config/subcategorias" class="text-gray-600 text-sm block">SubCategorias</a></li>
            </ul>
        </li>

        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/ventas" class="text-gray-700 font-medium">Pedidos</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/soporte" class="text-gray-700 font-medium"><i class="fa fa-tools mr-2"></i>Soporte Técnico</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full">
            <a href="/perunet/" class="text-gray-700 font-medium flex items-center">
                <i class="fa fa-home mr-2"></i>Volver al inicio
            </a>
        </li>
    </ul>
    
    <div class="flex flex-col pb-4 w-full bg-gray-50 pt-2 border-t border-gray-200">
        <a class="w-full pl-6 py-2 hover:bg-red-100 transition text-red-600 flex items-center gap-2" href="/perunet/logout">
            <i class="fa fa-sign-out-alt text-xl w-7 text-center"></i>
            <span class="font-medium">Salir</span>
        </a>
    </div>

    <script>
    function toggleSubmenu(id, element) {
        const submenu = document.getElementById(id);
        const icon = element.querySelector('.fa-chevron-down');
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            localStorage.setItem(id, 'open');
        } else {
            submenu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem(id, 'closed');
        }
    }

    // Restore state on load
    document.addEventListener('DOMContentLoaded', () => {
        ['ventas-submenu', 'config-submenu'].forEach(id => {
            const state = localStorage.getItem(id);
            if (state === 'open') {
                const submenu = document.getElementById(id);
                const parent = submenu.parentElement;
                const icon = parent.querySelector('.fa-chevron-down');
                
                submenu.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
    </script>
</nav>