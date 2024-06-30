<?php

require_once 'helpers.php';
require 'loadEnv.php';

if (getenv('APP_ENV') === 'production') {
    $client_id = getenv('CLIENT_ID_PROD');
    $client_secret = getenv('CLIENT_SECRET_PROD');
    $redirect_uri = getenv('REDIRECT_URI_PROD');
    $login_url = getenv('LOGIN_URL_PROD');
    $website = getenv('WEBSITE_PROD');
    $domain = getenv('DOMAIN_PROD');
} else {
    $client_id = getenv('CLIENT_ID_DEV');
    $client_secret = getenv('CLIENT_SECRET_DEV');
    $redirect_uri = getenv('REDIRECT_URI_DEV');
    $login_url = getenv('LOGIN_URL_DEV');
    $website = getenv('WEBSITE_DEV');
    $domain = getenv('DOMAIN_DEV');
}

handleCallback($client_id, $client_secret, $redirect_uri, $domain, $website, $login_url);
?>