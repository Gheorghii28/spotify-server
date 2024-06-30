<?php

require_once 'helpers.php';
require 'loadEnv.php';

if (getenv('APP_ENV') === 'production') {
    $client_id = getenv('CLIENT_ID_PROD');
    $redirect_uri = getenv('REDIRECT_URI_PROD');
} else {
    $client_id = getenv('CLIENT_ID_DEV');
    $redirect_uri = getenv('REDIRECT_URI_DEV');
}

handleLogin($client_id, $redirect_uri);
?>