<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        putenv(sprintf('%s=%s', trim($name), trim($value)));
    }
}

// Load the .env file based on the 'APP_ENV' environment variable
$env = getenv('APP_ENV') ?: 'development'; // Default environment

if ($env === 'production') {
    loadEnv(__DIR__ . '/.env.production');
} else {
    loadEnv(__DIR__ . '/.env.development');
}
?>
