<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanPlan - Gestión Financiera Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-chart-line mr-2"></i>FinanPlan
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-semibold">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">
                Gestiona tus Finanzas de Forma Inteligente
            </h1>
            <p class="text-xl mb-8 text-blue-100">
                Controla tus ingresos, gastos, presupuestos y alcanza tus metas financieras
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>Comenzar Gratis
                </a>
                <a href="{{ route('login') }}" class="bg-blue-700 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-800 transition shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Características Principales
                </h2>
                <p class="text-xl text-gray-600">
                    Todo lo que necesitas para tener control total de tus finanzas
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-wallet text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Gestión de Cuentas</h3>
                    <p class="text-gray-600">
                        Administra múltiples cuentas bancarias, tarjetas de crédito y efectivo en un solo lugar
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exchange-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Registro de Transacciones</h3>
                    <p class="text-gray-600">
                        Registra y categoriza todos tus ingresos y gastos de manera simple y rápida
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calculator text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Presupuestos Inteligentes</h3>
                    <p class="text-gray-600">
                        Crea presupuestos por categoría y recibe alertas cuando te acerques al límite
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bullseye text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Metas de Ahorro</h3>
                    <p class="text-gray-600">
                        Define objetivos financieros y monitorea tu progreso hacia cada meta
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-pie text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Informes Detallados</h3>
                    <p class="text-gray-600">
                        Visualiza gráficos y reportes completos sobre tus hábitos financieros
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Recordatorios</h3>
                    <p class="text-gray-600">
                        Configura alertas para pagos importantes y nunca olvides una fecha límite
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                ¿Listo para tomar control de tus finanzas?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Únete a FinanPlan y comienza a gestionar tu dinero de manera inteligente
            </p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-lg">
                <i class="fas fa-user-plus mr-2"></i>Crear Cuenta Gratis
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">
                &copy; 2025 FinanPlan. Todos los derechos reservados.
            </p>
            <p class="text-gray-500 text-sm mt-2">
                Gestión Financiera Personal
            </p>
        </div>
    </footer>
</body>
</html>