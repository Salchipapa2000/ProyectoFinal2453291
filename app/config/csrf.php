<?php

function generarTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validarTokenCSRF(): bool
{
    if (
        !isset($_SESSION['csrf_token']) ||
        !isset($_POST['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $_POST['csrf_token']
    );
}

function campoTokenCSRF(): string
{
    $token = htmlspecialchars(
        generarTokenCSRF(),
        ENT_QUOTES,
        'UTF-8'
    );

    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
