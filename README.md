# marketdashlight
A lightweight market dashboard built with reveal.js getting data from an API with an API key.

```
/var/www/yourproject/
├── config/
│   ├── .env              # Actual .env file containing sensitive data (not in version control)
│   └── .env.example      # Example .env file showing format without actual secrets
├── public/               # Publicly accessible directory (set as the web root in Apache)
│   ├── index.html        # Main HTML file, served directly to users
│   └── api_proxy.php     # PHP script that retrieves data from the API using the API key
├── src/                  # Directory for additional application logic, if needed
└── .gitignore            # Git ignore file to exclude sensitive files like `.env`
```

## Update Apache Configuration

In /etc/apache2/sites-available/yourdomain.conf, set DocumentRoot to /var/www/yourproject/public:

```
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/yourproject/public

    <Directory /var/www/yourproject/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Reload Apache

Apply changes by reloading Apache:

```
sudo systemctl reload apache2
```
