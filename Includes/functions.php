<?php

function getToken()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
    $maxLength = strlen($characters);
    $token = '';
    for ($i = 0; $i < 9; $i++) {
        $token .= $characters[rand(0, $maxLength - 1)];
    }
    // var_dump($token);
    // echo "<script>console.log('token: " . $token . "')</script>";
    return $token;
}
?>