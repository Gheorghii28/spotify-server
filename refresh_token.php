<?php
// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return only the headers and not the content
    // This is just to respond to the preflight request
    exit;
}

require_once 'helpers.php';
require 'loadEnv.php';

if (getenv('APP_ENV') === 'production') {
    $client_id = getenv('CLIENT_ID_PROD');
    $client_secret = getenv('CLIENT_SECRET_PROD');
    $login_url = getenv('LOGIN_URL_PROD');
    $domain = getenv('DOMAIN_PROD');
    $website = getenv('WEBSITE_PROD');
} else {
    $client_id = getenv('CLIENT_ID_DEV');
    $client_secret = getenv('CLIENT_SECRET_DEV');
    $login_url = getenv('LOGIN_URL_DEV');
    $domain = getenv('DOMAIN_DEV');
    $website = getenv('WEBSITE_DEV');
}

// CORS Headers
header("Access-Control-Allow-Origin: $website");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

handleRefreshToken($client_id, $client_secret, $login_url, $domain);
?>