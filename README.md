# marketdashlight
A lightweight market dashboard built with reveal.js getting data from an API with an API key.

/var/www/yourproject/
├── config/
│   ├── .env              # Actual .env file containing sensitive data (not in version control)
│   └── .env.example      # Example .env file showing format without actual secrets
├── public/               # Publicly accessible directory (set as the web root in Apache)
│   ├── index.html        # Main HTML file, served directly to users
│   └── api_proxy.php     # PHP script that retrieves data from the API using the API key
├── src/                  # Directory for additional application logic, if needed
└── .gitignore            # Git ignore file to exclude sensitive files like `.env`
