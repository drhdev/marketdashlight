<?php
// Function to load environment variables securely
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at " . $path);
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Set environment variables securely using putenv or $_SERVER
        if (!isset($_SERVER[$name])) {
            putenv("$name=$value");
            $_SERVER[$name] = $value;
        }
    }
}

// Define path to .env file in the config directory
$envPath = __DIR__ . '/../config/.env';

try {
    // Load the .env file
    loadEnv($envPath);

    // Retrieve the API key and URL
    $apiKey = $_SERVER['API_KEY'] ?? '';
    $apiUrl = $_SERVER['API_URL'] ?? null;

    // Check if API URL is configured
    if (!$apiUrl) {
        throw new Exception("API URL is not configured.");
    }

    // Build the URL securely, adding the API key only if it's provided
    $url = $apiUrl;
    if (!empty($apiKey)) {
        $url .= '?apikey=' . urlencode($apiKey);
    }

    // Use cURL for HTTP request with error handling and timeout
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        throw new Exception("Failed to retrieve data from API. HTTP Code: $httpCode. cURL Error: $curlError");
    }

    // Set headers for JSON response
    header('Content-Type: application/json');
    header('Cache-Control: no-store');
    echo $response;

} catch (Exception $e) {
    // Log error to a file or monitoring system (do not expose in the API response)
    error_log($e->getMessage());

    // Return a generic error message to the client
    header('Content-Type: application/json');
    header('Cache-Control: no-store');
    echo json_encode(['error' => 'An error occurred while processing the request. Please try again later.']);
}
