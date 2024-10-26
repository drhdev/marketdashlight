<?php
// Function to load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at " . $path);
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Define the path to the .env file in the config directory
$envPath = __DIR__ . '/../config/.env';

// Load the .env file
loadEnv($envPath);

// Retrieve the API key
$apiKey = $_ENV['API_KEY'] ?? null;

if (!$apiKey) {
    echo json_encode(['error' => 'API key is not configured.']);
    exit;
}

// API call
$url = 'https://api.example.com/data?apikey=' . $apiKey;
$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(['error' => 'Failed to retrieve data from API.']);
} else {
    echo $response;
}
?>
