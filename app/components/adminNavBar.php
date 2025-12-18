<header>
    <nav class="bg-gray-100 dark:bg-darkbg text-gray-800 dark:text-gray-100 py-3 px-4 flex items-center h-16 w-full fixed z-20 shadow-sm relative">
        <!-- Icono grande para PC, hamburguesa solo en móvil -->
        <div class="hidden md:flex items-center justify-center w-16 h-16">
            <?php
            $icon = '';
            if (isset($title)) {
                switch (true) {
                    case stripos($title, 'Usuario') !== false:
                        $icon = '<svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>';
                        break;
                    case stripos($title, 'Producto') !== false:
                        $icon = '<svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 3v4M8 3v4m-4 4h16"/></svg>';
                        break;
                    case stripos($title, 'Venta') !== false:
                        $icon = '<svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/><circle cx="7" cy="21" r="1"/><circle cx="17" cy="21" r="1"/></svg>';
                        break;
                    case stripos($title, 'Categoría') !== false:
                        $icon = '<svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18M15 3v18"/></svg>';
                        break;
                    case stripos($title, 'Marca') !== false:
                        $icon = '<svg class="w-10 h-10 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12l2 2 4-4"/></svg>';
                        break;
                    case stripos($title, 'Modelo') !== false:
                        $icon = '<svg class="w-10 h-10 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="10" x="3" y="7" rx="2"/><path d="M7 7V3h10v4"/></svg>';
                        break;
                    case stripos($title, 'Rol') !== false:
                        $icon = '<svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0113 0"/></svg>';
                        break;
                    default:
                        $icon = '<svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>';
                }
            }
            echo $icon;
            ?>
        </div>
        <!-- Menú hamburguesa solo en móvil -->
        <svg id="menu-icon" class="w-7 h-7 text-blue-600 dark:text-blue-400 cursor-pointer hover:bg-blue-100 dark:hover:bg-darkpanel rounded transition p-1 md:hidden absolute left-4 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1025 1024">
            <path fill="currentColor" d="M896.428 640h-768q-53 0-90.5-37.5T.428 512t37.5-90.5t90.5-37.5h768q53 0 90.5 37.5t37.5 90.5t-37.5 90.5t-90.5 37.5zm0-384h-768q-53 0-90.5-37.5T.428 128t37.5-90.5t90.5-37.5h768q53 0 90.5 37.5t37.5 90.5t-37.5 90.5t-90.5 37.5zm-768 512h768q53 0 90.5 37.5t37.5 90.5t-37.5 90.5t-90.5 37.5h-768q-53 0-90.5-37.5T.428 896t37.5-90.5t90.5-37.5z" />
        </svg>
        <div class="flex-1 flex items-center justify-between absolute left-0 right-0 pointer-events-none px-20">
            <a href="/perunet/admin" class="text-xl font-bold tracking-wide text-blue-700 dark:text-blue-400 text-center pointer-events-auto truncate">
                <?=isset($title) ? $title : 'Panel de Administración' ?>
            </a>
            <div class="flex items-center gap-3 pointer-events-auto">
                <a href="/perunet/admin" class="text-blue-400 hover:text-blue-600 text-2xl">
                    <i class="fas fa-th-large"></i>
                </a>
                <a href="/perunet/usuario/perfil" class="text-blue-400 hover:text-blue-600 text-2xl">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
        <div class="flex gap-2 items-center text-lg ml-auto relative z-10">
            <div class="flex items-center gap-2 px-2 py-1 rounded-xl bg-blue-50 dark:bg-darkpanel">

                <span class="font-bold text-blue-700 dark:text-blue-400 max-sm:hidden"><?=$_SESSION['usuario']['nombre'] ?? 'Usuario' ?></span>
            </div>
        </div>
    </nav>
    <script>
    // Menú hamburguesa funcional para mostrar/ocultar el menú lateral en móvil
    const menuIcon = document.getElementById('menu-icon');
    const adminMenuNav = document.querySelector('.admin-sidebar, .adminMenuNav');
    if(menuIcon && adminMenuNav) {
        menuIcon.addEventListener('click', () => {
            adminMenuNav.classList.toggle('open');
            adminMenuNav.classList.toggle('closed');
        });
    }
    </script>
</header>
