<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - <?php echo SITE_TITLE; ?></title>
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
        <!-- Sidebar (Same as Dashboard) -->
        <div class="w-64 bg-nepalBlue text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-blue-800">
                SMM Nepal
            </div>
            <nav class="flex-1 p-4">
                <a href="<?php echo BASE_URL; ?>/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/neworder" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-cart-plus mr-2"></i> New Order
                </a>
                <a href="<?php echo BASE_URL; ?>/orders" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 bg-blue-800 mb-2">
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
                <h2 class="text-xl font-semibold text-gray-800">Orders</h2>
                <div class="flex items-center">
                     <div class="bg-nepalRed text-white px-3 py-1 rounded-full text-sm">
                        Balance: <?php echo CURRENCY_SYMBOL . ' ...'; ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Service
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Link
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Charge
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo $order['order_id']; ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['service_name']); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap truncate w-32"><?php echo htmlspecialchars($order['order_url']); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo $order['order_quantity']; ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo CURRENCY_SYMBOL . ' ' . number_format($order['order_charge'], 2); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                        <span class="relative"><?php echo ucfirst($order['order_status']); ?></span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo $order['order_create']; ?></p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
