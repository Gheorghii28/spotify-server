<?php

require_once 'helpers.php';
require 'loadEnv.php';

if (getenv('APP_ENV') === 'production') {
    $client_id = getenv('CLIENT_ID_PROD');
    $client_secret = getenv('CLIENT_SECRET_PROD');
    $login_url = getenv('LOGIN_URL_PROD');
} else {
    $client_id = getenv('CLIENT_ID_DEV');
    $client_secret = getenv('CLIENT_SECRET_DEV');
    $login_url = getenv('LOGIN_URL_DEV');
}

handleRefreshToken($client_id, $client_secret, $login_url);
?>