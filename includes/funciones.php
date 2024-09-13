<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

//para calcular precio y saber cuando cambia de id el servicio
function esUltimo(string $actual, string $proximo): bool {
    if($actual !== $proximo) {
        return true;
    }
    return false;
}

//función que revisa si el usuario esta autenticado
function isAuth(): void
{
    if (!isset($_SESSION['login'])) {
        header('location: /');
    }
}
//función que revisa si el usuario es administrador
function isAdmin(): void
{
    if (!isset($_SESSION['admin'])) {
        header('location: /');
    }
}
function mostrarNotificacion($codigo)
{
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = ' Actualizado Correctamente';
            break;
        case 3:
            $mensaje = ' Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}

