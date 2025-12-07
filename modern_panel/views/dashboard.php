<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_TITLE; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nepalRed: '#DC143C',
                        nepalBlue: '#003893',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-nepalBlue text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-blue-800">
                SMM Nepal
            </div>
            <nav class="flex-1 p-4">
                <a href="<?php echo BASE_URL; ?>/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 bg-blue-800 mb-2">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/neworder" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-cart-plus mr-2"></i> New Order
                </a>
                <a href="<?php echo BASE_URL; ?>/orders" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-list mr-2"></i> Orders
                </a>
                <a href="<?php echo BASE_URL; ?>/addfunds" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-wallet mr-2"></i> Add Funds
                </a>
                <a href="<?php echo BASE_URL; ?>/services" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-tags mr-2"></i> Services
                </a>
            </nav>
            <div class="p-4 border-t border-blue-800">
                <a href="<?php echo BASE_URL; ?>/logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-600 text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-4">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
                    <div class="bg-nepalRed text-white px-3 py-1 rounded-full text-sm">
                        Balance: <?php echo CURRENCY_SYMBOL . ' ' . number_format($user['balance'], 2); ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Stat Card 1 -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-nepalBlue">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-nepalBlue mr-4">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Spent</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo CURRENCY_SYMBOL . ' ' . number_format($user['spent'], 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-nepalRed">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-nepalRed mr-4">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_orders; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $pending_orders; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity / News (Placeholder) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent News</h3>
                    <div class="border-l-4 border-nepalBlue pl-4 py-2 bg-blue-50 rounded">
                        <p class="text-gray-700 font-medium">Welcome to the new SMM Nepal Panel!</p>
                        <p class="text-gray-500 text-sm">We have updated our interface to serve you better. Enjoy the new Nepali flavor!</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
