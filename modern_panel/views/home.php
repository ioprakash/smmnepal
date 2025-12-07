<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
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
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-nepalBlue text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold text-nepalRed drop-shadow-md">SMM Nepal</a>
            <div>
                <a href="<?php echo BASE_URL; ?>/login" class="mr-4 hover:text-gray-300">Login</a>
                <a href="<?php echo BASE_URL; ?>/signup" class="bg-nepalRed px-4 py-2 rounded hover:bg-red-700 transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to SMM Nepal</h1>
        <p class="text-lg text-gray-600 mb-8">The Best Social Media Marketing Panel in Nepal</p>
        <a href="<?php echo BASE_URL; ?>/signup" class="bg-nepalBlue text-white px-6 py-3 rounded-lg text-xl hover:bg-blue-800 transition">Get Started</a>
    </div>
</body>
</html>
