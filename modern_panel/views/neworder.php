<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order - <?php echo SITE_TITLE; ?></title>
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
                <a href="<?php echo BASE_URL; ?>/neworder" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-800 bg-blue-800 mb-2">
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
                <h2 class="text-xl font-semibold text-gray-800">New Order</h2>
                <div class="flex items-center">
                     <div class="bg-nepalRed text-white px-3 py-1 rounded-full text-sm">
                        Balance: <?php echo CURRENCY_SYMBOL . ' ...'; ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
                    <?php if (isset($success)): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category</label>
                            <select id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-nepalBlue" onchange="filterServices()">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="service">Service</label>
                            <select id="service" name="service_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-nepalBlue" required>
                                <option value="">Select Service</option>
                                <!-- Options populated by JS -->
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="link">Link</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-nepalBlue" id="link" name="link" type="text" placeholder="https://..." required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">Quantity</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-nepalBlue" id="quantity" name="quantity" type="number" placeholder="1000" required>
                        </div>

                        <div class="flex items-center justify-between">
                            <button class="bg-nepalBlue hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition" type="submit">
                                Submit Order
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        const services = <?php echo json_encode($services); ?>;

        function filterServices() {
            const categoryId = document.getElementById('category').value;
            const serviceSelect = document.getElementById('service');
            serviceSelect.innerHTML = '<option value="">Select Service</option>';

            const filteredServices = services.filter(s => s.category_id == categoryId);
            filteredServices.forEach(s => {
                const option = document.createElement('option');
                option.value = s.service_id;
                option.text = `${s.service_name} - ${s.service_price} per 1000`;
                serviceSelect.appendChild(option);
            });
        }
    </script>
</body>
</html>
