<?php

function generateRandomString($length = 16)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function redirect($url)
{
    header('Location: ' . $url);
    exit();
}

function handleLogin($client_id, $redirect_uri)
{
    $state = generateRandomString(16);
    $scope = 'user-read-private user-read-email playlist-read-private playlist-modify-public user-library-read user-top-read user-follow-read user-follow-modify user-read-playback-state user-modify-playback-state user-library-modify';

    $queryParams = http_build_query([
        'response_type' => 'code',
        'client_id' => $client_id,
        'scope' => $scope,
        'redirect_uri' => $redirect_uri,
        'state' => $state,
        'show_dialog' => 'true'
    ]);

    redirect('https://accounts.spotify.com/authorize?' . $queryParams);
}

function handleCallback($client_id, $client_secret, $redirect_uri, $domain, $website, $login_url)
{
    $code = $_GET['code'] ?? null;
    $state = $_GET['state'] ?? null;

    if (is_null($state)) {
        redirect('/?' . http_build_query(['error' => 'state_mismatch']));
    }

    $authOptions = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
            'method'  => 'POST',
            'content' => http_build_query([
                'code' => $code,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code'
            ])
        ]
    ];
    $context = stream_context_create($authOptions);
    $response = file_get_contents('https://accounts.spotify.com/api/token', false, $context);
    $body = json_decode($response, true);

    if ($response !== false && isset($body['access_token'])) {
        setcookie('accessToken', $body['access_token'], time() + 3600, '/', $domain, false, false);
        setcookie('refreshToken', $body['refresh_token'], time() + 3600, '/', $domain, false, false);
        redirect($website);
    } else {
        redirect($login_url);
    }
}

function handleRefreshToken($client_id, $client_secret, $login_url)
{
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['query'])) {
        $refresh_token = $input['query'];
    }
    $authOptions = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
            'method'  => 'POST',
            'content' => http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token
            ])
        ]
    ];
    $context = stream_context_create($authOptions);
    $response = file_get_contents('https://accounts.spotify.com/api/token', false, $context);
    $body = json_decode($response, true);

    if ($response !== false && isset($body['access_token'])) {
        header('Content-Type: application/json');
        echo json_encode($body);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get access token']);
        redirect($login_url);
    }
}
?>