# marketdashlight
A lightweight market dashboard built with reveal.js getting data from an API with an API key.

```
/var/www/yourproject/
├── config/
│   ├── .env              # Actual .env file containing sensitive data (not in version control)
│   └── .env.example      # Example .env file showing format without actual secrets
├── public/               # Publicly accessible directory (set as the web root in Apache)
│   ├── index.html        # Main HTML file, served directly to users
│   ├── api_proxy.php     # PHP script that retrieves data from the API using the API key
│   ├── styles.css        # External CSS file for styling
│   ├── config.js         # JavaScript configuration file for constants
│   ├── app.js            # Main JavaScript application file
│   └── images/           # Directory for image files
│       └── market_logo.png # Example image file
├── src/                  # Directory for additional application logic, if needed
└── .gitignore            # Git ignore file to exclude sensitive files like `.env`
```

To set up your GitHub repository `marketdashlight` on your Apache web server at `/var/www/`, ensuring that `index.html` loads when you visit `https://example.com`, follow these steps:

### Step 1: Clone the Repository

1. SSH into your server and navigate to the `/var/www/` directory.
   ```bash
   cd /var/www/
   ```

2. Clone the GitHub repository `marketdashlight`:
   ```bash
   sudo git clone https://github.com/drhdev/marketdashlight.git
   ```

3. Rename the cloned directory to match your desired web root (`yourproject`):
   ```bash
   sudo mv marketdashlight yourproject
   ```

### Step 2: Configure Apache to Serve `index.html` from `/var/www/yourproject/public`

1. Open the Apache configuration file for your domain. You may have a file located at `/etc/apache2/sites-available/example.com.conf` or similar.
   ```bash
   sudo nano /etc/apache2/sites-available/example.com.conf
   ```

2. Update the `DocumentRoot` to point to the `public` directory within your project:
   ```apache
   <VirtualHost *:80>
       ServerName example.com
       DocumentRoot /var/www/yourproject/public

       <Directory /var/www/yourproject/public>
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog ${APACHE_LOG_DIR}/error.log
       CustomLog ${APACHE_LOG_DIR}/access.log combined
   </VirtualHost>
   ```

3. Enable the configuration and reload Apache:
   ```bash
   sudo a2ensite example.com.conf
   sudo systemctl reload apache2
   ```

### Step 3: Set Up the `.env` File

1. Create the `.env` file in `/var/www/yourproject/config`:
   ```bash
   sudo nano /var/www/yourproject/config/.env
   ```

2. Add your API key to this `.env` file:
   ```plaintext
   API_KEY=your_secret_api_key
   ```

3. Ensure that the `.env` file has restricted permissions:
   ```bash
   sudo chmod 600 /var/www/yourproject/config/.env
   sudo chown www-data:www-data /var/www/yourproject/config/.env
   ```

### Step 4: Secure the `.env` File

To ensure the `.env` file isn’t publicly accessible, you can add a rule to Apache to prevent access to `.env` files:

1. Open your Apache configuration file or `.htaccess` file in the project directory.
   ```bash
   sudo nano /var/www/yourproject/public/.htaccess
   ```

2. Add the following:
   ```apache
   <Files ".env">
       Order allow,deny
       Deny from all
   </Files>
   ```

### Step 5: Verify Permissions and Ownership

Ensure Apache has the necessary permissions for all project files and directories:

```bash
sudo chown -R www-data:www-data /var/www/yourproject
sudo chmod -R 755 /var/www/yourproject
```

### Step 6: Test the Setup

1. Visit `https://example.com` in your browser.
2. `index.html` should load, and the JavaScript in the file should make a request to `api_proxy.php` in the `/public` directory.
3. If everything is working correctly, you should see the market data displayed.

With this setup, `index.html` will load directly at `https://example.com`, while your sensitive `.env` file remains secure outside the `/public` directory.

