<?php

use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWT($authHeader)
{
    if (is_null($authHeader)) {
        throw new Exception("Otentikasi JWT Gagal", 1);
    }
    return explode(' ', $authHeader)[1];
}

function validateJWT($encodedToken)
{
    $key = getenv('SECRET_KEY');
    JWT::$leeway = 60;
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    $model = new UserModel();
    $model->getEmail($decodedToken->email);
}

function createJWT($email)
{
    $requestTime = time();
    $tokenTime = getenv('JWT_TIME_TO_LIVE');
    $notbefore = $requestTime + 10;
    $expiredTime = $requestTime + $tokenTime;

    $payload = [
        'iat' => $requestTime,
        'nbf' => $notbefore,
        'exp' => $expiredTime,
        'email' => $email
    ];
    $jwt = JWT::encode($payload, getenv('SECRET_KEY'), 'HS256');
    return $jwt;
}
