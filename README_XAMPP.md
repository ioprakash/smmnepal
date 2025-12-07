# How to Run on XAMPP

1.  **Locate your XAMPP installation**: Usually `C:\xampp`.
2.  **Go to the `htdocs` folder**: `C:\xampp\htdocs`.
3.  **Create a folder**: Name it `smmnepal`.
4.  **Copy Files**: Copy the **entire contents** of your project folder (`d:\code\smmnepal`) into `C:\xampp\htdocs\smmnepal`.
    *   Your structure should look like:
        *   `C:\xampp\htdocs\smmnepal\modern_panel\public\index.php`
        *   `C:\xampp\htdocs\smmnepal\Database.sql`
        *   etc.
5.  **Start XAMPP**: Open XAMPP Control Panel and start **Apache** and **MySQL**.
6.  **Run Setup**:
    *   Open your browser and go to: `http://localhost/smmnepal/modern_panel/public/setup_xampp.php`
    *   This will create the database `smmnepal` and import the necessary tables.
7.  **Access the Panel**:
    *   After setup, go to: `http://localhost/smmnepal/modern_panel/public/`

## Troubleshooting
-   If you see "Access denied for user 'root'@'localhost'", ensure your XAMPP MySQL has no password (default). If you set a password, update `modern_panel/core/Database.php`.
-   If images or assets are broken, you might need to update `BASE_URL` in `modern_panel/core/Config.php`.
