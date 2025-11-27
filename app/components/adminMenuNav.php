<nav id="menu" class="bg-gray-50 border-r border-gray-200 flex flex-col justify-between h-[calc(100vh-4rem)] mt-3 fixed max-md:hidden w-56 shadow-sm transition-transform duration-500">
    <ul class="flex flex-col gap-2 py-4">
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/" class="text-gray-700 font-medium">Perfil</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/usuarios" class="text-gray-700 font-medium">Usuarios</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/productos" class="text-gray-700 font-medium">Productos</a></li>
        <li class="px-6 py-2 bg-blue-50 rounded-xl transition w-full">
            <a class="text-blue-700 font-semibold" href="#">Ventas</a>
            <ul class="ml-4 mt-1">
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/ventas" class="text-gray-700">Listado</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/ventas/reporte" class="text-gray-700">Reporte</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/ventas/resumen" class="text-gray-700">Resumen</a></li>
            </ul>
        </li>
        <li class="px-6 py-2 bg-blue-50 rounded-xl transition w-full">
            <a class="text-blue-700 font-semibold" href="#">Configuración</a>
            <ul class="ml-4 mt-1">
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/config/roles" class="text-gray-700">Roles</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/config/marcas" class="text-gray-700">Marcas</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/config/modelos" class="text-gray-700">Modelos</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/config/categorias" class="text-gray-700">Categorias</a></li>
                <li class="hover:bg-blue-100 px-2 rounded transition"><a href="/perunet/admin/config/subcategorias" class="text-gray-700">SubCategorias</a></li>
            </ul>
        </li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/ventas" class="text-gray-700 font-medium">Pedidos</a></li>
        <li class="px-6 py-2 hover:bg-blue-100 rounded-l-lg transition w-full"><a href="/perunet/admin/soporte" class="text-gray-700 font-medium"><i class="fa fa-tools mr-2"></i>Soporte Técnico</a></li>
    </ul>
    <a class="w-full pl-6 py-2 mb-4 rounded-l-full hover:bg-blue-100 transition text-gray-500 flex items-center gap-2" href="/perunet/">
        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1188 1000">
            <path fill="currentColor" d="m912 236l276 266l-276 264V589H499V413h413V236zM746 748l106 107q-156 146-338 146q-217 0-365.5-143.5T0 499q0-135 68-250T251.5 67.5T502 1q184 0 349 148L746 255Q632 151 503 151q-149 0-251.5 104T149 509q0 140 105.5 241T502 851q131 0 244-103z" />
        </svg>
        <span class="font-medium">Volver</span>
    </a>
</nav>