<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - <?php echo SITE_TITLE; ?></title>
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
                <a href="<?php echo BASE_URL; ?>/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 mb-2">
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
                <a href="<?php echo BASE_URL; ?>/account" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 bg-blue-800 mb-2">
                    <i class="fas fa-user-cog mr-2"></i> Account
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
                <h2 class="text-xl font-semibold text-gray-800">Account Settings</h2>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Balance:</span>
                    <span class="font-bold text-nepalBlue"><?php echo CURRENCY_SYMBOL . ' ' . number_format($user['balance'], 2); ?></span>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Change Password -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-lock mr-2 text-nepalBlue"></i> Change Password
                        </h3>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Current Password</label>
                                <input type="password" name="current_password" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                                <input type="password" name="new_password" required minlength="8"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                                <input type="password" name="confirm_password" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <button type="submit" name="change_password"
                                class="bg-nepalBlue hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                Change Password
                            </button>
                        </form>
                    </div>

                    <!-- API Key -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-key mr-2 text-nepalBlue"></i> API Key
                        </h3>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Your API Key</label>
                            <div class="flex">
                                <input type="text" value="<?php echo htmlspecialchars($user['apikey']); ?>" readonly
                                    class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 bg-gray-100">
                                <button onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($user['apikey']); ?>')"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 rounded-r">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('Are you sure? This will invalidate your current API key.')">
                            <button type="submit" name="regenerate_api"
                                class="bg-nepalRed hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                <i class="fas fa-sync mr-2"></i> Regenerate API Key
                            </button>
                        </form>
                    </div>

                    <!-- Timezone -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-clock mr-2 text-nepalBlue"></i> Timezone
                        </h3>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Timezone Offset (hours)</label>
                                <select name="timezone" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <?php for ($i = -12; $i <= 14; $i += 0.5): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($user['timezone'] == $i) ? 'selected' : ''; ?>>
                                            GMT <?php echo ($i >= 0 ? '+' : '') . $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" name="update_timezone"
                                class="bg-nepalBlue hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                                Update Timezone
                            </button>
                        </form>
                    </div>

                    <!-- Account Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-info-circle mr-2 text-nepalBlue"></i> Account Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600 font-semibold">Name:</span>
                                <span class="text-gray-800"><?php echo htmlspecialchars($user['name']); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-semibold">Username:</span>
                                <span class="text-gray-800"><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-semibold">Email:</span>
                                <span class="text-gray-800"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-semibold">Member Since:</span>
                                <span class="text-gray-800"><?php echo date('M d, Y', strtotime($user['register_date'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
