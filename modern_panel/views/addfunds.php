<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Funds - <?php echo SITE_TITLE; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nepalRed: '#DC143C',
                        nepalBlue: '#003893',
                        khalti: '#5C2D91',
                        esewa: '#60BB46',
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
                <a href="<?php echo BASE_URL; ?>/orders" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
                    <i class="fas fa-list mr-2"></i> Orders
                </a>
                <a href="<?php echo BASE_URL; ?>/addfunds" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 bg-blue-800 mb-2">
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
                <h2 class="text-xl font-semibold text-gray-800">Add Funds</h2>
                <!-- User Info (Simplified) -->
                <div class="flex items-center">
                    <div class="bg-nepalRed text-white px-3 py-1 rounded-full text-sm">
                        Balance: <?php echo CURRENCY_SYMBOL . ' ...'; ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Choose Payment Method</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Khalti -->
                        <div class="border rounded-lg p-4 hover:shadow-lg transition cursor-pointer border-khalti">
                            <div class="flex justify-center mb-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/Khalti_Digital_Wallet_Logo.png.jpg" alt="Khalti" class="h-12">
                            </div>
                            <h4 class="text-center font-bold text-khalti mb-2">Khalti</h4>
                            <p class="text-center text-sm text-gray-500 mb-4">Pay with Khalti Digital Wallet</p>
                            <button class="w-full bg-khalti text-white py-2 rounded hover:bg-purple-800 transition">Pay Now</button>
                        </div>

                        <!-- eSewa -->
                        <div class="border rounded-lg p-4 hover:shadow-lg transition cursor-pointer border-esewa">
                            <div class="flex justify-center mb-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/ff/Esewa_logo.png" alt="eSewa" class="h-12">
                            </div>
                            <h4 class="text-center font-bold text-esewa mb-2">eSewa</h4>
                            <p class="text-center text-sm text-gray-500 mb-4">Pay with eSewa Mobile Wallet</p>
                            <button class="w-full bg-esewa text-white py-2 rounded hover:bg-green-600 transition">Pay Now</button>
                        </div>

                        <!-- IME Pay (Placeholder) -->
                        <div class="border rounded-lg p-4 hover:shadow-lg transition cursor-pointer border-red-500">
                            <div class="flex justify-center mb-4">
                                <div class="h-12 w-12 bg-red-500 rounded-full flex items-center justify-center text-white font-bold">IME</div>
                            </div>
                            <h4 class="text-center font-bold text-red-500 mb-2">IME Pay</h4>
                            <p class="text-center text-sm text-gray-500 mb-4">Pay with IME Pay</p>
                            <button class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">Pay Now</button>
                        </div>
                    </div>

                    <!-- Dynamic Methods from DB -->
                    <?php if (!empty($methods)): ?>
                    <div class="mt-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">Other Methods</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php foreach ($methods as $method): ?>
                                <div class="border rounded-lg p-4 hover:shadow-lg transition cursor-pointer">
                                    <h4 class="text-center font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($method['methodVisibleName']); ?></h4>
                                    <button class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-900 transition">Pay Now</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
