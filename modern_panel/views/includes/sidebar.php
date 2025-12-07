<!-- Sidebar Navigation -->
<div class="w-64 bg-nepalBlue text-white flex flex-col">
    <div class="p-6 text-2xl font-bold text-center border-b border-blue-800">
        SMM Nepal
    </div>
    <nav class="flex-1 p-4">
        <a href="<?php echo BASE_URL; ?>/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
        <a href="<?php echo BASE_URL; ?>/neworder" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/neworder') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-cart-plus mr-2"></i> New Order
        </a>
        <a href="<?php echo BASE_URL; ?>/orders" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/orders') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-list mr-2"></i> Orders
        </a>
        <a href="<?php echo BASE_URL; ?>/addfunds" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/addfunds') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-wallet mr-2"></i> Add Funds
        </a>
        <a href="<?php echo BASE_URL; ?>/services" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/services') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-tags mr-2"></i> Services
        </a>
        <a href="<?php echo BASE_URL; ?>/account" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/account') !== false) ? 'bg-blue-800' : ''; ?>">
            <i class="fas fa-user-cog mr-2"></i> Account
        </a>
    </nav>
    <div class="p-4 border-t border-blue-800">
        <a href="<?php echo BASE_URL; ?>/logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-600 text-center">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</div>
